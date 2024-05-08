<?php


namespace App\Interfaces;

use Illuminate\Http\Request;
use \Illuminate\Http\JsonResponse;

interface CRUDController
{
    /**
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request): JsonResponse;

    /**
     * @param Request $request
     * @return mixed
     */
    public function create(Request $request): JsonResponse;

    /**
     * @param Request $request
     * @return mixed
     */
    public function update(Request $request): JsonResponse;

    /**
     * @param Request $request
     * @return mixed
     */
    public function delete(Request $request): JsonResponse;

    /**
     * @param Request $request
     * @return mixed
     */
    public function read(Request $request): JsonResponse;
}
