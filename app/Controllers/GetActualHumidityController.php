<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Model\Services\WeatherRepository;
use Contributte\ApiRouter\ApiRoute;
use Nette\Application\Request;
use Nette\Application\Response;
use App\Utils\Responses\ExtendedJsonResponse as JsonResponse;
use Nette\Http\IResponse;
use Tracy\Debugger;


/**
 * API for logging users in
 *
 * @ApiRoute(
 * 	"/api/get_actual_humidity",
 *
 * 	methods={
 * 		"GET"="run"
 * 	},
 *  presenter="GetActualHumidity"
 * )
 */
final class GetActualHumidityController extends AbstractController
{

    /** @var WeatherRepository @inject */
    public WeatherRepository $weatherRepository;

	public function run(Request $request): Response
	{
        try {

            $humidity = $this->weatherRepository->findOneWeatherBy([], ['date' => 'DESC'])->getHumidity();

            return new JsonResponse($this->apiResponseFormatter->formatPayload(['humidity' => $humidity]), IResponse::S200_OK);

        } catch (\Exception $e) {
            return new JsonResponse($this->apiResponseFormatter->formatError($e->getCode(), $e->getMessage()), IResponse::S500_InternalServerError);
        }

	}
}
