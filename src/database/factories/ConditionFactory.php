<?php

namespace Database\Factories;

use App\Models\Condition;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConditionFactory extends Factory
{
    /**
     * モデルの名前を定義
     *
     * @var string
     */
    protected $model = Condition::class;

    /**
     * モデルのデフォルトの状態を定義
     *
     * @return array
     */
    public function definition()
    {
        // conditionテーブルに挿入する条件を指定
        return [
            'condition' => $this->faker->randomElement([
                '良好',
                '目立った傷や汚れなし',
                'やや傷や汚れあり',
                '状態が悪い',
            ]),
        ];
    }
}
