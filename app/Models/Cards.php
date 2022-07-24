<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cards extends Model
{
    private $faces;
    private $suits;
    private $deck;

    function __construct()
    {
        $this->faces = ['A' , '2', '3', '4', '5', '6', '7', '8', '9', '10', 'J', 'Q', 'K'];
        $this->suits = ['S', 'H', 'D', 'C'];
        $this->deck = [];
    }


    public function buildCardDeck()
    {
        foreach ($this->suits as $suit) {
            foreach ($this->faces as $face) {
                $this->deck[] = "{$face}{$suit}";
            }
        }

        return $this->deck;
    }


    public function getDeckShuffled()
    {
        $cardDeck = $this->buildCardDeck();
        if (shuffle($cardDeck)) {
            $this->setDeck($cardDeck);
            return $cardDeck;
        } else {
            return false;
        }

    }

    public function getRandomCards(array $cardDeck, int $numberOfCards)
    {
        $cards = [];
        $randomKeys = array_rand($cardDeck, $numberOfCards);
        if($numberOfCards === 1){
            $cards[] = $cardDeck[$randomKeys];
        } else {
            foreach ($randomKeys as $key) {
                $cards[] = $cardDeck[$key];
            }
        }

        return $cards;
    }

    public function updateDeckCards(array $cardDeck, array $cardToBeRemoved)
    {
        $updatedDeck = array_diff($cardDeck, $cardToBeRemoved);
        $this->setDeck($updatedDeck);
        return $this->getDeck();
    }

    public function getDeck()
    {
        return $this->deck;
    }

    public function setDeck($deck)
    {
        $this->deck = $deck;
    }

}
