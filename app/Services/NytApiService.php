<?php

namespace App\Services;

use App\Exceptions\BadRequestException;
use App\Exceptions\UnauthorizedException;
use App\Http\Requests\SearchRequest;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Client\Factory as HttpClient;

readonly class NytApiService
{

    public function __construct(private HttpClient $httpClient)
    {
    }

    public function search(SearchRequest $request): array
    {
        $requestQuery = [];
        $request->getAuthor() && $requestQuery['author'] = $request->getAuthor();
        $request->getTitle() && $requestQuery['title'] = $request->getTitle();
        $request->getIsbn() && $requestQuery['isbn'] = $request->getIsbn();
        $requestQuery['offset'] = $request->getOffset();

        $response = $this->httpClient->get(
            Config::get('nyt_api.url') . '/books/v3/lists/best-sellers/history.json',
            array_merge([
                'api-key' => Config::get('nyt_api.key'),
            ], $requestQuery)
        );

        if ($response->getStatusCode() == Response::HTTP_UNAUTHORIZED) {
            throw new UnauthorizedException();
        }

        if (!$response->successful()) {
            throw new BadRequestException();
        }

        return $response->json();
    }
}
