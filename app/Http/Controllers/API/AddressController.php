<?php

namespace App\Http\Controllers\API;

use App\AddressType;
use App\Http\Controllers\Controller;
use App\Repository\AddressRepository;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $addressRepo;

    public function __construct(AddressRepository $addressRepository)
    {
        $this->addressRepo = $addressRepository;
    }

    public function getTypes()
    {
        return self::showResponse(true, AddressType::all());
    }


    public function add(Request $request)
    {
        $phone = $this->addressRepo->create($request['entity_id'], $request['entity_type'], $request['address'], $request['address_type']);
        return self::showResponse(true, $phone);
    }

    public function edit(Request $request, $id)
    {
        $phone = $this->addressRepo->update($id, $request['address'], $request['address_type']);
        return self::showResponse(true, $phone);
    }

    public function delete($id)
    {
        return self::showResponse($this->addressRepo->delete($id));
    }
}