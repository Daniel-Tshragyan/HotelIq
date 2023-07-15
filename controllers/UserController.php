<?php


namespace controllers;

use models\Statistic;
use models\User;
use models\Work;


class UserController extends Controller
{

    public function index()
    {
        $user = new User();
        $data = $user->getAll();
        return $this->render('user/index', $data);
    }

    public function getUserStatistic($id)
    {
        $startDate = date("Y/m/d", strtotime('2020-08-31')) . ' 00:00:00';
        $endDate = date("Y/m/d", strtotime('2020-10-01')) . ' 23:59:59';
        $user = new User();
        $statistics = $user->findStatistics($id, $startDate, $endDate);

        $data = [
            'days' => [],
            'total' => 0,
            'headers' => [
                'date',
                'start',
                'end',
                'general',
                'ingoing',
                'income',
                'price',
            ]
        ];

        foreach ($statistics as $statistic) {
            $day = explode(' ', $statistic['start'])[0];
            if (!isset($data['days'][$day])) {
                $data['days'][$day] = [
                    'price' => 0,
                    'general' => 0,
                    'income' => 0,
                    'ingoing' => 0,
                ];
            }
            if ($statistic['work']) {
                $data['days'][$day]['price'] = $data['days'][$day]['price'] + $statistic['price'] + ($statistic['towels'] * Statistic::TOWEL_PRICE) + ($statistic['bed'] * Statistic::BED_PRICE);
                if ($statistic['name'] === Work::WORK_GENERAL_NAME) {
                    $data['days'][$day]['general'] += 1;
                } else if ($statistic['name'] === Work::WORK_INGOING_NAME) {
                    $data['days'][$day]['ingoing'] += 1;
                } else if ($statistic['name'] === Work::WORK_INCOME_NAME) {
                    $data['days'][$day]['income'] += 1;
                }
                $data['total'] += $statistic['price'] + ($statistic['towels'] * Statistic::TOWEL_PRICE) + ($statistic['bed'] * Statistic::BED_PRICE);
            } else {
                $data['days'][$day]['start'] = $statistic['start'];
                $data['days'][$day]['end'] = $statistic['end'];
            }

        }

        return json_encode($data);
    }

    public function getUserStatisticByDay($id, $date)
    {
        $startDate = $date . ' 00:00:00';
        $endDate = $date . ' 23:59:59';
        $user = new User();
//        echo "<pre>";
//        print_r($user->findStatisticsByDay($id, $startDate, $endDate));
//        die;
        $statistics = $user->findStatisticsByDay($id, $startDate, $endDate);

        $data = [
            'data' => [],
            'total' => 0,
            'headers' => [
                'room(build)',
                'room type',
                'work type',
                'start',
                'end',
                'price',
            ]
        ];
        foreach ($statistics as $statistic) {
            $data['data'][] = [
                'price' => $statistic['price'] + ($statistic['towels'] * Statistic::TOWEL_PRICE) + ($statistic['bed'] * Statistic::BED_PRICE),
                'room' => $statistic['num'] . '(' . $statistic['build_name'] . ')',
                'work_type' => $statistic['work_type'],
                'type' => $statistic['room_type'] ,
                'start' => $statistic['start'],
                'end' => $statistic['end'],
            ];
            $data['total'] += $statistic['price'] + ($statistic['towels'] * Statistic::TOWEL_PRICE) + ($statistic['bed'] * Statistic::BED_PRICE);
        }
        return json_encode($data);
    }
}