<?php

namespace BlueRestAPI\Controller;

use App\Http\Controllers\Controller;
use BlueRestAPI\Model\Exception\CannotCreateItemException;
use BlueRestAPI\Model\Exception\CannotRemoveItemException;
use BlueRestAPI\Model\Exception\CannotUpdateItemException;
use BlueRestAPI\Model\Exception\ItemNotFoundException;
use BlueRestAPI\Model\ItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * Class ItemController
 * @package BlueRestAPI\Controller
 */
class ItemController extends Controller
{
    /**
     * @var ItemRepository
     */
    private $repository;

    /**
     * ItemController constructor.
     * @param ItemRepository $repository
     */
    public function __construct(ItemRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        try {
            return new JsonResponse($this->repository->findById((int)$id));
        } catch (ItemNotFoundException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        }
    }

    /**
     * @return JsonResponse
     */
    public function showAll(): JsonResponse
    {
        return new JsonResponse($this->repository->findAll());
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'amount' => 'required',
        ]);

        if ($validator->fails()) {
            return new JsonResponse(['error' => 'Name and amount are required!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $item = $this->repository->create($request->name, $request->amount);
            if ($item->id) {
                return new JsonResponse([
                    'message' => 'Item has been created',
                    'resourceId' => $item->id
                ], JsonResponse::HTTP_CREATED);
            }
        } catch (ItemNotFoundException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (CannotCreateItemException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'amount' => 'required'
        ]);

        if (!$validator->fails()) {
            return new JsonResponse(['error' => 'Name and amount are required!'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $item = $this->repository->update($request->id, $request->name, $request->amount);
            return new JsonResponse([
                'message' => 'Item has been changed',
                'resourceId' => $item->id
            ]);
        } catch (ItemNotFoundException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (CannotUpdateItemException $exception) {
            return new JsonResponse(['error' => $exception->getMessage(), JsonResponse::HTTP_NOT_MODIFIED]);
        }
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        try {
            $this->repository->delete($id);
            return new JsonResponse(['message' => 'Item has been removed properly from storage']);
        } catch (ItemNotFoundException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_NOT_FOUND);
        } catch (CannotRemoveItemException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
