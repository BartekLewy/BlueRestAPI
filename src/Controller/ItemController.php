<?php

namespace BlueRestAPI\Controller;

use App\Http\Controllers\Controller;
use BlueRestAPI\Model\Item;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class ItemController
 * @package BlueRestAPI\Controller
 */
class ItemController extends Controller
{
    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $item = Item::find($id);

        if (!$item) {
            return new JsonResponse(['error' => 'This item does not exists'], JsonResponse::HTTP_NOT_FOUND);
        }

        return new JsonResponse($item);
    }

    /**
     * @return JsonResponse
     */
    public function showAll(): JsonResponse
    {
        $items = Item::all();
        return response()->json($items);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $item = new Item();
        $item->name = $request->name;
        $item->amount = $request->amount;
        $item->save();

        if ($item->id) {
            return new JsonResponse([
                'message' => 'Item has been created',
                'resourceId' => $item->id
            ], JsonResponse::HTTP_CREATED);
        }

        return new JsonResponse(['error' => 'Sorry, something went wrong and i could not create item'], JsonResponse::HTTP_BAD_REQUEST);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        /** @var Item $item */
        $item = Item::find($request->id);

        if (!$item) {
            return new JsonResponse(['error' => 'This item does not exist'], JsonResponse::HTTP_NOT_FOUND);
        }

        $item->name = $request->name ?? $item->name;
        $item->amount = $request->amount ?? $item->amount;
        $item->save();

        if ($item->wasChanged()) {
            return new JsonResponse(['message' => 'Item has been changed']);
        }
        return new JsonResponse([], JsonResponse::HTTP_NOT_MODIFIED);
    }

    /**
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function destroy($id): JsonResponse
    {
        /** @var Item $item */
        $item = Item::find($id);

        if (!$item) {
            return new JsonResponse(['error' => 'This item does not exist'], JsonResponse::HTTP_NOT_FOUND);
        }
        $item->delete();

        if ($item->trashed()) {
            return new JsonResponse(['message' => 'Item has been removed properly from storage']);
        }
        return new JsonResponse(['error' => 'Sorry, something went wrong. Remember, name and amount fields cannot be empty!'], JsonResponse::HTTP_BAD_REQUEST);
    }
}
