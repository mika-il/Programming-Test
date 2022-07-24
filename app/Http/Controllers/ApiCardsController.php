<?php

namespace App\Http\Controllers;

use App\Models\Cards;
use App\Http\Requests\CardsRequest;
use Illuminate\Http\Request;

class ApiCardsController extends Controller
{
    private $cards;
    private const TOTAL_CARDS = 52;

    function __construct(Cards $cards)
    {
        $this->cards = $cards;
    }


    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(CardsRequest $request)
    {
        $playerCards = [];
        $cardToBeRemoved = [];
        $players = (int) $request->players > self::TOTAL_CARDS ? self::TOTAL_CARDS : $request->players;
        $numberOfCards = $this->getNumberOfCards($players);
        $deckCards = $this->cards->getDeckShuffled();
        for($i=0; $i < $players; $i++) {
            if($i > 0) {
                //dd($deckCards, $cardToBeRemoved);
                $deckCards = $this->cards->updateDeckCards($deckCards, $cardToBeRemoved);
            }

            $getCards = $this->cards->getRandomCards($deckCards, $numberOfCards);
            $playerCards[] = $getCards;
            $cardToBeRemoved = $getCards;
        }

        return response()->json([
            'cards' => $playerCards
        ], 200);
    }

    private function getNumberOfCards(int $people)
    {
        return intdiv(self::TOTAL_CARDS , $people);
    }
}
