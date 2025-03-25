<?php

namespace App\Http\Controllers;

use App\Exceptions\BadRequestException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\SearchRequest;
use App\Services\NytApiService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class SearchController extends Controller
{

    public function __construct(
        private readonly NytApiService $nytApiService,
    ) {
    }

    public function search(SearchRequest $request): JsonResponse
    {
        try {
            $data = $this->nytApiService->search($request);
        } catch (UnauthorizedException $e) {
            return response()->json()->setStatusCode(Response::HTTP_UNAUTHORIZED);
        } catch (BadRequestException $e) {
            return response()->json()->setStatusCode(Response::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return response()->json()->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return response()->json(['status' => 'ok', 'results' => $data]);
    }
}
