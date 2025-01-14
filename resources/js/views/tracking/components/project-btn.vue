<template>
    <v-menu
        :close-on-content-click="false"
        :nudge-width="200"
        offset-y
        v-model="shown"
    >
        <template v-slot:activator="{ on, attrs }">
            <v-btn
                tile
                small
                text
                :color="color"
                v-bind="attrs"
                v-on="on"
            >
                <span v-if="!selectedProject">
                    <v-icon>
                        mdi-plus-circle-outline
                    </v-icon>
                    &nbsp;&nbsp;{{langMap.tracking.project_btn.project_or_ticket}}
                </span>
                <span v-if="selectedProject">
                    <span v-if="selectedProject && selectedProject.from">
                        Ticket: {{ selectedProject.number }}.
                    </span>
                    <span v-else>
                        Project:
                    </span>
                    {{ selectedProject.name }}
                </span>
            </v-btn>
        </template>
        <v-card
            max-width="400"
            class="d-flex pa-2"
            style="overflow: hidden"
        >
            <v-expansion-panels
                v-model="panels"
            >
                <v-expansion-panel>
                    <v-expansion-panel-header>
                        {{langMap.tracking.project_btn.choose_project}}
                    </v-expansion-panel-header>
                    <v-expansion-panel-content
                        style="min-height: 380px; max-height: 380px"
                    >
                        <v-text-field
                            :label="langMap.tracking.project_btn.project"
                            :placeholder="langMap.tracking.project_btn.start_typing_to_search"
                            autofocus
                            clearable
                            v-model="search"
                            style="max-width: 90%"
                        >
                        </v-text-field>
                        <perfect-scrollbar>
                            <v-treeview
                                :items="$store.getters['Projects/getTreeProjects']"
                                item-children="projects"
                                item-text="name"
                                item-key="id"
                                dense
                                class="text-left"
                                :selected-color="color"
                                activatable
                                hoverable
                                open-on-click
                                return-object
                                style="min-height: 300px; max-height: 300px;"
                                @update:active="selectProject"
                            >
                                <template v-slot:prepend="{ item }">
                                    <v-icon small v-if="item.supplier_type === 'App\\Company'">
                                        mdi-factory
                                    </v-icon>
                                    <v-icon v-else>mdi-folder-account-outline</v-icon>
                                </template>
                                <template v-slot:label="{ item }">
                                    <span v-if="item.projects">
                                        {{ item.name }}
                                    </span>
                                    <span v-else>
                                        {{ item.name }}
                                        <small>({{ item.product.name }})</small>
                                    </span>
                                </template>
                            </v-treeview>
                        </perfect-scrollbar>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                    <v-expansion-panel-header>
                        {{langMap.tracking.project_btn.create_new_project}}
                    </v-expansion-panel-header>
                    <v-expansion-panel-content
                        style="min-height: 380px; max-height: 380px;"
                    >
                        <v-list>
                            <v-list-item>
                                <v-text-field
                                    v-model="form.name"
                                    :label="langMap.tracking.project_btn.project_name"
                                    :placeholder="langMap.tracking.project_btn.type_project_name_here"
                                    clearable
                                    required
                                    full-width
                                ></v-text-field>
                            </v-list-item>
                            <v-list-item>
                                <v-autocomplete
                                    v-model="form.product"
                                    :items="getFilteredProducts"
                                    :loading="isLoadingSearchProduct"
                                    :search-input.sync="searchProduct"
                                    color="white"
                                    item-text="name"
                                    item-value="id"
                                    :label="langMap.tracking.project_btn.product"
                                    :placeholder="langMap.tracking.project_btn.start_typing_to_search"
                                    return-object
                                    clearable
                                    required
                                ></v-autocomplete>
                            </v-list-item>
                            <v-list-item>
                                <v-autocomplete
                                    v-model="form.client"
                                    :items="getFilteredClients"
                                    :loading="isLoadingSearchClient"
                                    :search-input.sync="searchClient"
                                    color="white"
                                    item-text="name"
                                    item-value="id"
                                    :label="langMap.tracking.project_btn.client"
                                    :placeholder="langMap.tracking.project_btn.start_typing_to_search"
                                    return-object
                                    clearable
                                    required
                                ></v-autocomplete>
                            </v-list-item>
                            <v-list-item>
                                <v-text-field
                                    v-model="form.color"
                                    hide-details
                                    class="ma-0 pa-0"
                                    solo
                                    :label="langMap.tracking.project_btn.color"
                                    required
                                >
                                    <template v-slot:append>
                                        <v-menu v-model="colorMenu" top nudge-bottom="105" nudge-left="16" :close-on-content-click="false">
                                            <template v-slot:activator="{ on }">
                                                <div :style="switchColor" v-on="on" />
                                            </template>
                                            <v-card>
                                                <v-card-text class="pa-0">
                                                    <v-color-picker v-model="form.color" flat />
                                                </v-card-text>
                                            </v-card>
                                        </v-menu>
                                    </template>
                                </v-text-field>
                            </v-list-item>
                        </v-list>
                        <v-card-actions>
                            <v-spacer></v-spacer>

                            <v-btn
                                color="error"
                                text
                                @click="resetNewProjectForm(); menu = false"
                            >
                                {{langMap.tracking.project_btn.cancel}}
                            </v-btn>
                            <v-btn
                                color="success"
                                text
                                :disabled="!createProjectValid"
                                @click="createNewProject"
                            >
                                {{langMap.tracking.project_btn.save}}
                            </v-btn>
                        </v-card-actions>
                    </v-expansion-panel-content>
                </v-expansion-panel>
                <v-expansion-panel>
                    <v-expansion-panel-header>
                        {{langMap.tracking.project_btn.choose_ticket}}
                    </v-expansion-panel-header>
                    <v-expansion-panel-content
                        style="min-height: 380px; max-height: 380px;"
                    >
                        <v-text-field
                            :label="langMap.tracking.project_btn.ticket"
                            :placeholder="langMap.tracking.project_btn.start_typing_to_search"
                            autofocus
                            clearable
                            v-model="searchTicket"
                            style="max-width: 90%"
                        >
                        </v-text-field>
                        <perfect-scrollbar>
                            <v-treeview
                                :items="$store.getters['Tickets/getTreeTickets']"
                                item-children="tickets"
                                item-text="number"
                                item-key="id"
                                dense
                                class="text-left"
                                :selected-color="color"
                                activatable
                                hoverable
                                open-on-click
                                return-object
                                style="min-height: 300px; max-height: 300px;"
                                @update:active="selectTicket"
                            >
                                <template v-slot:prepend="{ item }">
                                    <v-icon small v-if="item.from_entity_type === 'App\\Company'">
                                        mdi-factory
                                    </v-icon>
                                    <v-icon small v-else-if="item.from_entity_type === 'App\\Client'">
                                        mdi-account
                                    </v-icon>
                                    <v-icon v-else>mdi-folder-account-outline</v-icon>
                                </template>
                                <template v-slot:label="{ item }">
                                    <span v-if="item.tickets">
                                        {{ item.name }}
                                    </span>
                                    <span v-else>
                                        {{ item.number }}
                                        <small>({{ item.name }})</small>
                                    </span>
                                </template>
                            </v-treeview>
                        </perfect-scrollbar>
                    </v-expansion-panel-content>
                </v-expansion-panel>
            </v-expansion-panels>
        </v-card>
    </v-menu>
</template>

<style scoped>
>>>.v-treeview--dense .v-treeview-node__root {
    min-height: 1.1em;
}
>>>.v-treeview-node__root .v-icon {
    font-size: 20px;
}
>>>.v-treeview--dense .v-treeview-node__label {
    max-width: 80%;
}
</style>

<style src="vue2-perfect-scrollbar/dist/vue2-perfect-scrollbar.css"/>

<script>

import _ from 'lodash';
import { PerfectScrollbar } from 'vue2-perfect-scrollbar';
import * as Helper from "../helper";

export default {
    components: {
        PerfectScrollbar
    },
    props: {
        color: {
            type: String,
            default: '#ffffff'
        },
        onChoosable: {
            type: Function
        },
        value: {
            required: true
        }
    },
    data() {
        return {
            langMap: this.$store.state.lang.lang_map,
            shown: false,
            panels: 0,
            menu: false,
            search: '',
            isLoadingProject: false,
            isLoadingSearchProduct: false,
            isLoadingSearchClient: false,
            nameLimit: 20,
            isCreatingProject: false,
            searchProduct: null,
            searchClient: null,
            searchTicket: null,
            colorMenu: false,
            form: {
                name: '',
                product: null,
                client: null,
                color: Helper.genRandomColor()
            }
        };
    },
    created() {
        this.debounceGetProjects = _.debounce(this.__getProjects, 1000);
        this.debounceGetProducts = _.debounce(this.__getProducts, 1000);
        this.debounceGetClients = _.debounce(this.__getClients, 1000);
        this.debounceGetTickets = _.debounce(this.__getTickets, 1000);
    },
    mounted() {
        // this.debounceGetProducts();
        // this.debounceGetProjects()
    },
    methods: {
        __getProjects() {
            this.$store.dispatch('Projects/getProjectList', { search: this.search });
        },
        __getProducts() {
            this.$store.dispatch('Products/getProductList', { search: this.searchProduct });
        },
        __getClients() {
            this.$store.dispatch('Clients/getClientList', { search: this.searchClient });
        },
        __getTickets() {
            this.$store.dispatch('Tickets/getTicketList', { search: this.searchTicket });
        },
        resetNewProjectForm() {
            this.form = {
                name: '',
                product: null,
                client: null,
                color: Helper.genRandomColor()
            };
            this.panels = 0;
        },
        createNewProject() {
            if (this.createProjectValid) {
                this.menu = false;
                this.$store.dispatch('Projects/createProject', this.form)
                    .then(project => (this.selectedProject = project));
                this.resetNewProjectForm();
            }
        },
        selectProject(project) {
            this.selectedProject = project.shift();
            this.shown = false;
        },
        selectTicket(ticket) {
            this.selectedTicket = ticket.shift();
            this.shown = false;
        }
    },
    computed: {
        selectedProject: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            }
        },
        selectedTicket: {
            get() {
                return this.value;
            },
            set(val) {
                this.$emit('input', val);
            }
        },
        getFilteredProducts() {
            return this.$store.getters["Products/getProducts"].map(entry => {
                const name = entry.name.length > this.nameLimit
                    ? entry.name.slice(0, this.nameLimit) + '...'
                    : entry.name

                return Object.assign({}, entry, { name })
            })
        },
        getFilteredClients() {
            return this.$store.getters['Clients/getClients'].map(entry => {
                const name = entry.name.length > this.nameLimit
                    ? entry.name.slice(0, this.nameLimit) + '...'
                    : entry.name

                return Object.assign({}, entry, { name })
            })
        },
        switchColor() {
            const { form: { color }, colorMenu } = this
            return {
                backgroundColor: color,
                cursor: 'pointer',
                height: '30px',
                width: '30px',
                borderRadius: colorMenu ? '50%' : '4px',
                transition: 'border-radius 200ms ease-in-out'
            }
        },
        createProjectValid() {
            return this.form.name && this.form.product && this.form.client && this.form.color;
        }
    },
    watch: {
        search() {
            this.debounceGetProjects();
        },
        searchTicket() {
            this.debounceGetTickets();
        }
    }
};
</script>
