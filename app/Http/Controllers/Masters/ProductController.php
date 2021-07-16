<?php

namespace App\Http\Controllers\Masters;

use App\Constant\DBCode;
use App\Constant\DBMessage;
use App\Http\Controllers\Controller;
use App\Models\Masters\Product;
use Exception;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    protected $product;

    public function __construct()
    {
        $this->product = new product();
    }

    public function show(Request $request)
    {
        try {
            $data = $this->product->withJoin($this->product->defaultSelects);
            return $this->jsonSuccess(null, datatables()->eloquent($data)
                ->with('start', intval($request->start))
                ->toJson()
                ->getOriginalContent()
            );
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function find($id)
    {
        try {
            $row = $this->product->withJoin($this->product->defaultSelects)
                ->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            return $this->jsonSuccess(null, $row);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function store(Request $request)
    {
        try {
            $this->product->create($request->all());

            return $this->jsonSuccess(DBMessage::SUCCESS_ADD);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function update(Request $request, $id)
    {
        try {

            $row = $this->product->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            $row->update($request->all());

            return $this->jsonSuccess(DBMessage::SUCCESS_EDIT);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }

    public function delete($id)
    {
        try {

            $row = $this->product->find($id);

            if (is_null($row))
                throw new Exception(DBMessage::ERROR_CORRUPT_DATA, DBCode::AUTHORIZED_ERROR);

            $row->delete();

            return $this->jsonSuccess(DBMessage::SUCCESS_DELETED);
        } catch (Exception $e) {
            return $this->jsonError($e);
        }
    }
}
