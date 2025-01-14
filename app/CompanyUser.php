<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;

class CompanyUser extends Model
{
    use SoftDeletes, HasRoles;

    protected $table = 'company_users';

    protected $appends = ['roles', 'role_names'];

    public function getRoleNamesAttribute()
    {
        $result = null;
        $roles = $this->getRolesAttribute();
        $translationsArray = Language::find(Auth::user()->language_id)->lang_map;
        foreach ($roles as $key => $role) {
            $roleName = $role->name;
            $result .= $translationsArray->roles->$roleName;
            $result .= $key !== count($roles) - 1 ? ', ' : '';
        }
        return $result ?? $translationsArray->roles->contact;
    }

    public function getRolesAttribute()
    {
        $roleIds = $this->roleIds();
        return Role::whereIn('id', $roleIds)->get();
    }

    public function getPermissionIds()
    {
        $roleIds = $this->roleIds();
        if ($roleIds) {
            return RoleHasPermission::whereIn('role_id', $roleIds)->get()->pluck('permission_id')->toArray();
        }
        return [];
    }

    public function roleIds()
    {
        return ModelHasRole::where(['model_id' => $this->attributes['id'], 'model_type' => self::class])->get()->pluck('role_id')->toArray();
    }

    public function hasRoleId($id)
    {
        $roleIds = $this->roleIds();
        if ($roleIds) {
            return in_array($id, $roleIds, true);
        }
        return false;
    }

    public function hasPermissionId($id)
    {
        $id = is_int($id) ? [$id] : $id;
        $roleIds = $this->roleIds();
        if ($roleIds) {
            return RoleHasPermission::whereIn('role_id', $roleIds)->whereIn('permission_id', $id)->exists();
        }
        return false;
    }

    public function assignedToTeams(): HasMany
    {
        return $this->hasMany(TeamCompanyUser::class, 'company_user_id', 'id');
    }

    public function assignedToProducts(): HasMany
    {
        return $this->hasMany(ProductCompanyUser::class, 'company_user_id', 'id');
    }

    public function assignedToClients(): HasMany
    {
        return $this->hasMany(ClientCompanyUser::class, 'company_user_id', 'id');
    }

    public function userData(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function companyData(): HasOne
    {
        return $this->hasOne(Company::class, 'id', 'company_id');
    }

}
