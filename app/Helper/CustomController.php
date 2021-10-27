<?php

namespace App\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomController extends Controller
{

    protected $validationRules = [];

    protected $validationMessage = [];


    public function checkValidation(Request $request)
    {
        $data = $request->all();

        return Validator::make($data, $this->getValidationRules(), $this->getValidationMessage());
    }

    public function isAuth($credentials = [])
    {
        if (count($credentials) > 0 && Auth::attempt($credentials)) {
            return true;
        }

        return false;
    }

    /**
     * @return mixed
     */
    public function getValidationRules()
    {
        return $this->validationRules;
    }

    /**
     * @param mixed $validationRules
     *
     * @return CustomController
     */
    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidationMessage()
    {
        return $this->validationMessage;
    }

    /**
     * @param mixed $validationMessage
     *
     * @return CustomController
     */
    public function setValidationMessage($validationMessage)
    {
        $this->validationMessage = $validationMessage;

        return $this;
    }


}
