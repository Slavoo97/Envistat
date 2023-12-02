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
 * 	"/api/get_last_temperature",
 *
 * 	methods={
 * 		"GET"="run"
 * 	},
 *  presenter="GetLastTemperature"
 * )
 */
final class GetLastTemperatureController extends AbstractController
{

    /** @var WeatherRepository @inject */
    public WeatherRepository $weatherRepository;

	public function run(Request $request): Response
	{
        try {

			$lastWeekDate = new \DateTimeImmutable('-1 week');
			$result = [];
			$groupedData = [];
			foreach ($this->weatherRepository->findWeatherBy(['date' => $lastWeekDate->format('Y-m-d H:i:s')]) as $item) {
				$date = $item->getDate();
				if (!isset($groupedData[$date])) {
					$groupedData[$date] = ['totalTemperature' => 0, 'count' => 0];
				}

				$groupedData[$date]['totalTemperature'] += $item->getTemperature;
				$groupedData[$date]['count']++;
            }

			foreach ($groupedData as $date => $values) {
				$averageTemperature = $values['totalTemperature'] / $values['count'];

				$result[] = (object)['date' => $date, 'temperature' => $averageTemperature];
			}


            return new JsonResponse($this->apiResponseFormatter->formatPayload($result), IResponse::S200_OK);

        } catch (\Exception $e) {
            return new JsonResponse($this->apiResponseFormatter->formatError($e->getCode(), $e->getMessage()), IResponse::S500_InternalServerError);
        }

	}
}
