<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Testing</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
    <div class="px-4 py-5 my-2 text-center">
        <h1 class="display-5 fw-bold">The Playing Cards</h1>
        <div class="col-lg-6 mx-auto">
            <p class="lead mb-4">There are 52 cards containing 1-13 of each Spade(S), Heart(H), Diamond(D), Club(C) will be given to number of people randomly.</p>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                <form class="row g-3">
                    @csrf
                    <div class="col-auto">
                        <input type="text" class="form-control" name="players" placeholder="Input number of player">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-submit btn-primary mb-3">Submit</button>
                    </div>
                </form>
            </div>
            <div class="card-distributed p-5 mb-4 bg-light rounded-3 d-none"></div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $(".btn-submit").click(function(e){
                e.preventDefault();
                handleSubmit();
            });

            $('input[name="players"]').on('keydown', function(e) {
                if (e.which !== 13) {
                    return;
                }

                e.preventDefault();
                handleSubmit();
            });

            function handleSubmit() {
                const _token = $("input[name='_token']").val();
                const players = $("input[name='players']");

                if(!isElementValid(players)) {
                    return
                }

                $.ajax({
                    url: @json(route('api.cards')),
                    type:'POST',
                    data: {_token:_token, players:players.val()},
                    dataType: "json"
                })
                .done( function (response) {
                    makePlayerCardsList(response.cards);
                })
                .fail( function (err) {
                    const resultError = err.responseJSON.errors;
                    $.each(resultError, function (i, error) {
                        var element = $(document).find(`[name="${i}"]`);
                        element.addClass("is-invalid").removeClass("is-valid");
                        element.after(
                            $(`<em class="invalid-feedback">${error[0]}</em>`)
                        );
                    });
                });
            }

            function makePlayerCardsList(cards) {
                const cardDiv = document.querySelector(".card-distributed");
                let rowHTML = "";

                if(cards.length === 0) {
                    rowHTML = "Irregularity occurred.";
                } else {
                    cards.forEach( (card, index) => {
                        rowHTML += `<div>PLAYER ${index + 1} : `;
                        card.forEach( item => {
                            rowHTML += `${item} `;
                        });
                        rowHTML += `${ cards.length === index + 1 ? '' : ','}</div>`;
                    });
                }

                cardDiv.innerHTML = rowHTML;
                if(cardDiv.classList.contains("d-none")) {
                    cardDiv.classList.remove("d-none");
                }
            }

            function isElementValid(element) {
                element.next().remove("em");
                if(isElementEmpty(element) || isNotMatchNumericRanges(element)) {
                    element.addClass("is-invalid").removeClass("is-valid");
                    element.after(
                        $(`<em class="invalid-feedback">Input value does not exist or value is invalid</em>`)
                    );
                    return false;
                }

                element.addClass("is-valid").removeClass("is-invalid");
                return true;
            }

            function isElementEmpty(element) {
                return element.val() === '';
            }

            function isNotMatchNumericRanges(element) {
                const numericRangesPattern = /^[1-9][0-9]*$/;
                const isValid = numericRangesPattern.test(element.val());
                return numericRangesPattern.test(element.val()) ? false : true;
            }
        });
    </script>
</body>
</html>
