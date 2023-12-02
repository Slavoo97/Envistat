<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Model\Entity\Weather;
use App\Model\Services\WeatherRepository;
use Contributte\ApiRouter\ApiRoute;
use Nette\Application\Request;
use Nette\Application\Response;
use App\Utils\Responses\ExtendedJsonResponse as JsonResponse;
use Nette\Http\IResponse;


/**
 * API for post data into DB
 *
 * @ApiRoute(
 * 	"/api/post-data",
 * 	methods={
 * 		"POST"="run"
 * 	},
 *  presenter="WeatherPost",
 *  format="json"
 * )
 */
final class WeatherPostController extends AbstractController
{
    /** @var WeatherRepository @inject */
    public WeatherRepository $weatherRepository;

	public function run(Request $request): Response
	{
        try {
            $data = $this->getRequestData($request);
            $values = json_decode($data);

			$this->weatherRepository->create(
				$values->temperature,
				$values->humidity,
			);

			return new JsonResponse($this->apiResponseFormatter->formatMessage("ok"), IResponse::S200_OK);

        } catch (\Exception $e) {
            return new JsonResponse($this->apiResponseFormatter->formatError("500", $e->getMessage()), IResponse::S500_InternalServerError);
        }
	}

}
