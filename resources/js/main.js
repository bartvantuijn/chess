$(document).ready(function () {
    //todo: statussen checken
    //todo: ui verbeteren
    //todo: openingen aanvullen (https://www.chess.com/openings)
    //
    //todo: bugs
    //  - advantage doesn't correspond if you're playing black (scripts thinks black is opponent, which is actually dynamic)
    //  - you can move with black playing an opening or computer if you're really fast
    //  - selectGame function can trigger resetGame just before you've moved a piece, causing it to temporarily reset untill selectGame is triggerd again

    /*
     * A simple chess AI, by someone who doesn't know how to play chess.
     * Uses the chessboard.js and chess.js libraries.
     *
     * Copyright (c) 2020 Zhang Zeyu
     */

    var STACK_SIZE = 100; // maximum size of undo stack

    var board = null;
    var game = new Chess();
    var depth = 1; // default depth
    var globalSum = 0; // always from black's perspective. Negative for white's perspective.

    let computerSelected = null;
    let openingSelected = null;
    let gameSelected = null;
    let playersTurn = null;

    let computerTimer = null;
    let gameTimer = null;

    var whiteSquareGrey = '#a9a9a9';
    var blackSquareGrey = '#696969';

    var config = {
        orientation: 'white',
        position: 'start',
        draggable: true,
        showNotation: true,
        onDragStart: onDragStart,
        onDrop: onDrop,
        onMouseoutSquare: onMouseoutSquare,
        onMouseoverSquare: onMouseoverSquare,
        onSnapEnd: onSnapEnd,
    };
    board = Chessboard('board', config);

    /*
     * Event Listeners
     */
    $('#board').on('scroll touchmove touchend touchstart contextmenu', function (e) {
        e.preventDefault();
    });

    $('#compVsCompBtn').on('click', async function () {
        await resetPlayground();
        compVsComp('w');
    });

    // $('#resetBtn').on('click', async function () {
    //     await resetPlayground();
    // });

    /*
     * Livewire Event Listeners
     */
    Livewire.on('playgroundReset', async (data) => {
        console.log('playgroundReset');

        await resetGame();
    });

    Livewire.on('computerSelected', async (data) => {
        console.log('computerSelected', data.computer);

        await resetGame(undefined, undefined, data.computer.rating);

        computerSelected = data.computer;
    });

    Livewire.on('openingSelected', async (data) => {
        console.log('openingSelected', data.opening);

        await resetGame(data.opening.orientation, data.opening.pgn, 750);

        openingSelected = data.opening;
        window.setTimeout(() => makeBestMove(data.opening.orientation === 'black' ? 'w' : 'b'), 250);
    });

    Livewire.on('gameSelected', async (data) => {
        console.log('gameSelected', data.game, data.turn);

        await resetGame(data.turn, data.game.pgn, undefined);

        gameSelected = data.game;
        playersTurn = data.turn;

        gameTimer = window.setTimeout(function () {
            console.log('selectGame', gameSelected.id);
            Livewire.dispatch('selectGame', {
                id: gameSelected.id
            });
        }, 5000);
    });

    /*
     * Functions
     */
    async function resetPlayground() {
        return new Promise((resolve) => {
            console.log('resetPlayground');

            Livewire.dispatch('resetPlayground');

            let cleanup = Livewire.on('playgroundReset', (data) => {
                console.log('playgroundReset (resolved)');

                cleanup();
                resolve();
            });
        });
    }

    async function resetGame(orientation = 'white', pgn = null, rating = 250) {
        console.log('resetGame', orientation, pgn, rating);

        if (rating >= 750) {
            depth = 3;
        } else if (rating >= 500) {
            depth = 2;
        } else {
            depth = 1;
        }

        globalSum = 0;

        computerSelected = null;
        openingSelected = null;
        gameSelected = null;
        playersTurn = null;

        // Kill the computer timer
        if (computerTimer) {
            clearTimeout(computerTimer);
            computerTimer = null;
        }

        // Kill the game timer
        if (gameTimer) {
            clearTimeout(gameTimer);
            gameTimer = null;
        }

        await resetBoard(orientation, pgn);
        updateGame();
    }

    async function resetBoard(orientation, pgn) {
        return new Promise(resolve => {
            console.log('resetBoard', orientation, pgn || game.pgn());

            game.reset();
            game.load_pgn(pgn || game.pgn());

            board.orientation(orientation);
            board.position(game.fen());

            resolve();
        });
    }

    function updateGame(color = 'black') {
        console.log('updateGame', color, {computer: computerSelected, opening: openingSelected, game: gameSelected});

        // Update PGN
        $('#pgn').html(game.pgn() || '1.');
        $('#pgn').animate({scrollLeft: $('#pgn').get(0).scrollWidth}, 50);

        // Update Advantage
        if (globalSum > 0) {
            // Black is better
            $('#advantageWhite').addClass('hidden');
            $('#advantageBlack').removeClass('hidden').text(globalSum);
        } else if (globalSum < 0) {
            // White is better
            $('#advantageBlack').addClass('hidden');
            $('#advantageWhite').removeClass('hidden').text(-globalSum);
        } else {
            // Neither side is better
            $('#advantageWhite, #advantageBlack').addClass('hidden');
        }

        $('#advantageBar').css('width', ((-globalSum + 2000) / 4000) * 100 + '%');

        // Update Status
        updateStatus(color);

        // todo: move below to better place
        // Save Game
        if (computerTimer === null) {
            saveGame(color);
        }
    }

    function updateStatus(color = 'black') {
        console.log('updateStatus', color)

        let status = '';

        if (game.in_checkmate()) {
            status = `<b>Checkmate!</b> Oops, <b>${color}</b> lost.`;
        } else if (game.insufficient_material()) {
            status = `It's a <b>draw!</b> (Insufficient Material)`;
        } else if (game.in_threefold_repetition()) {
            status = `It's a <b>draw!</b> (Threefold Repetition)`;
        } else if (game.in_stalemate()) {
            status = `It's a <b>draw!</b> (Stalemate)`;
        } else if (game.in_draw()) {
            status = `It's a <b>draw!</b> (50-move Rule)`;
        } else if (game.in_check()) {
            status = `Oops, <b>${color}</b> is in <b>check!</b>`;
        } else {
            status = `No check, checkmate, or draw.`;
        }

        $('#status').html(status);
    }

    function saveGame(color = 'black') {
        if (color !== 'black') {
            return
        }

        console.log('saveGame', color, {computer: computerSelected, opening: openingSelected, game: gameSelected, status: checkStatus(false) ? 'completed' : 'in_progress'});
        Livewire.dispatch('saveGame', {
            depth: depth,
            computer: computerSelected,
            opening: openingSelected,
            game: gameSelected,
            pgn: game.pgn(),
            status: checkStatus(false) ? 'completed' : 'in_progress'
        });
    }

    //todo: improve withGameSelected
    function checkStatus(withGameSelected = true) {
        console.log('checkStatus', withGameSelected, gameSelected);
        if (withGameSelected) {
            return !!(gameSelected !== null || game.in_checkmate() || game.insufficient_material() || game.in_threefold_repetition() || game.in_stalemate() || game.in_draw());
        }

        return !!(game.in_checkmate() || game.insufficient_material() || game.in_threefold_repetition() || game.in_stalemate() || game.in_draw());
    }

    /*
     * Plays Computer vs. Computer, starting with a given color.
     */
    function compVsComp(color) {
        console.log('compVsComp');

        if (!checkStatus()) {
            computerTimer = window.setTimeout(function () {
                makeBestMove(color);
                compVsComp(color === 'b' ? 'w' : 'b');
            }, 250);
        }
    }

    var undo_stack = [];

    function undo() {
        var move = game.undo();
        undo_stack.push(move);

        // Maintain a maximum stack size
        if (undo_stack.length > STACK_SIZE) {
            undo_stack.shift();
        }
        board.position(game.fen());
    }

    $('#undoBtn').on('click', function () {
        if (game.history().length >= 2) {
            undo();
            window.setTimeout(() => undo(), 250);
        } else {
            // alert('Nothing to undo.');
        }
    });

    function redo() {
        game.move(undo_stack.pop());
        board.position(game.fen());
    }

    $('#redoBtn').on('click', function () {
        if (undo_stack.length >= 2) {
            redo();
            window.setTimeout(() => redo(), 250);
        } else {
            // alert('Nothing to redo.');
        }
    });

    /*
     * Piece Square Tables, adapted from Sunfish.py:
     * https://github.com/thomasahle/sunfish/blob/master/sunfish.py
     */

    var weights = {p: 100, n: 280, b: 320, r: 479, q: 929, k: 60000, k_e: 60000};
    var pst_w = {
        p: [
            [100, 100, 100, 100, 105, 100, 100, 100],
            [78, 83, 86, 73, 102, 82, 85, 90],
            [7, 29, 21, 44, 40, 31, 44, 7],
            [-17, 16, -2, 15, 14, 0, 15, -13],
            [-26, 3, 10, 9, 6, 1, 0, -23],
            [-22, 9, 5, -11, -10, -2, 3, -19],
            [-31, 8, -7, -37, -36, -14, 3, -31],
            [0, 0, 0, 0, 0, 0, 0, 0],
        ],
        n: [
            [-66, -53, -75, -75, -10, -55, -58, -70],
            [-3, -6, 100, -36, 4, 62, -4, -14],
            [10, 67, 1, 74, 73, 27, 62, -2],
            [24, 24, 45, 37, 33, 41, 25, 17],
            [-1, 5, 31, 21, 22, 35, 2, 0],
            [-18, 10, 13, 22, 18, 15, 11, -14],
            [-23, -15, 2, 0, 2, 0, -23, -20],
            [-74, -23, -26, -24, -19, -35, -22, -69],
        ],
        b: [
            [-59, -78, -82, -76, -23, -107, -37, -50],
            [-11, 20, 35, -42, -39, 31, 2, -22],
            [-9, 39, -32, 41, 52, -10, 28, -14],
            [25, 17, 20, 34, 26, 25, 15, 10],
            [13, 10, 17, 23, 17, 16, 0, 7],
            [14, 25, 24, 15, 8, 25, 20, 15],
            [19, 20, 11, 6, 7, 6, 20, 16],
            [-7, 2, -15, -12, -14, -15, -10, -10],
        ],
        r: [
            [35, 29, 33, 4, 37, 33, 56, 50],
            [55, 29, 56, 67, 55, 62, 34, 60],
            [19, 35, 28, 33, 45, 27, 25, 15],
            [0, 5, 16, 13, 18, -4, -9, -6],
            [-28, -35, -16, -21, -13, -29, -46, -30],
            [-42, -28, -42, -25, -25, -35, -26, -46],
            [-53, -38, -31, -26, -29, -43, -44, -53],
            [-30, -24, -18, 5, -2, -18, -31, -32],
        ],
        q: [
            [6, 1, -8, -104, 69, 24, 88, 26],
            [14, 32, 60, -10, 20, 76, 57, 24],
            [-2, 43, 32, 60, 72, 63, 43, 2],
            [1, -16, 22, 17, 25, 20, -13, -6],
            [-14, -15, -2, -5, -1, -10, -20, -22],
            [-30, -6, -13, -11, -16, -11, -16, -27],
            [-36, -18, 0, -19, -15, -15, -21, -38],
            [-39, -30, -31, -13, -31, -36, -34, -42],
        ],
        k: [
            [4, 54, 47, -99, -99, 60, 83, -62],
            [-32, 10, 55, 56, 56, 55, 10, 3],
            [-62, 12, -57, 44, -67, 28, 37, -31],
            [-55, 50, 11, -4, -19, 13, 0, -49],
            [-55, -43, -52, -28, -51, -47, -8, -50],
            [-47, -42, -43, -79, -64, -32, -29, -32],
            [-4, 3, -14, -50, -57, -18, 13, 4],
            [17, 30, -3, -14, 6, -1, 40, 18],
        ],

        // Endgame King Table
        k_e: [
            [-50, -40, -30, -20, -20, -30, -40, -50],
            [-30, -20, -10, 0, 0, -10, -20, -30],
            [-30, -10, 20, 30, 30, 20, -10, -30],
            [-30, -10, 30, 40, 40, 30, -10, -30],
            [-30, -10, 30, 40, 40, 30, -10, -30],
            [-30, -10, 20, 30, 30, 20, -10, -30],
            [-30, -30, 0, 0, 0, 0, -30, -30],
            [-50, -30, -30, -30, -30, -30, -30, -50],
        ],
    };
    var pst_b = {
        p: pst_w['p'].slice().reverse(),
        n: pst_w['n'].slice().reverse(),
        b: pst_w['b'].slice().reverse(),
        r: pst_w['r'].slice().reverse(),
        q: pst_w['q'].slice().reverse(),
        k: pst_w['k'].slice().reverse(),
        k_e: pst_w['k_e'].slice().reverse(),
    };

    var pstOpponent = {w: pst_b, b: pst_w};
    var pstSelf = {w: pst_w, b: pst_b};

    /*
     * Evaluates the board at this point in time,
     * using the material weights and piece square tables.
     */
    function evaluateBoard(game, move, prevSum, color) {

        if (game.in_checkmate()) {

            // Opponent is in checkmate (good for us)
            if (move.color === color) {
                return 10 ** 10;
            }
            // Our king's in checkmate (bad for us)
            else {
                return -(10 ** 10);
            }
        }

        if (game.in_draw() || game.in_threefold_repetition() || game.in_stalemate()) {
            return 0;
        }

        if (game.in_check()) {
            // Opponent is in check (good for us)
            if (move.color === color) {
                prevSum += 50;
            }
            // Our king's in check (bad for us)
            else {
                prevSum -= 50;
            }
        }

        var from = [
            8 - parseInt(move.from[1]),
            move.from.charCodeAt(0) - 'a'.charCodeAt(0),
        ];
        var to = [
            8 - parseInt(move.to[1]),
            move.to.charCodeAt(0) - 'a'.charCodeAt(0),
        ];

        // Change endgame behavior for kings
        if (prevSum < -1500) {
            if (move.piece === 'k') {
                move.piece = 'k_e';
            }
            // Kings can never be captured
            // else if (move.captured === 'k') {
            //   move.captured = 'k_e';
            // }
        }

        if ('captured' in move) {
            // Opponent piece was captured (good for us)
            if (move.color === color) {
                prevSum +=
                    weights[move.captured] +
                    pstOpponent[move.color][move.captured][to[0]][to[1]];
            }
            // Our piece was captured (bad for us)
            else {
                prevSum -=
                    weights[move.captured] +
                    pstSelf[move.color][move.captured][to[0]][to[1]];
            }
        }

        if (move.flags.includes('p')) {
            // NOTE: promote to queen for simplicity
            move.promotion = 'q';

            // Our piece was promoted (good for us)
            if (move.color === color) {
                prevSum -=
                    weights[move.piece] + pstSelf[move.color][move.piece][from[0]][from[1]];
                prevSum +=
                    weights[move.promotion] +
                    pstSelf[move.color][move.promotion][to[0]][to[1]];
            }
            // Opponent piece was promoted (bad for us)
            else {
                prevSum +=
                    weights[move.piece] + pstSelf[move.color][move.piece][from[0]][from[1]];
                prevSum -=
                    weights[move.promotion] +
                    pstSelf[move.color][move.promotion][to[0]][to[1]];
            }
        } else {
            // The moved piece still exists on the updated board, so we only need to update the position value
            if (move.color !== color) {
                prevSum += pstSelf[move.color][move.piece][from[0]][from[1]];
                prevSum -= pstSelf[move.color][move.piece][to[0]][to[1]];
            } else {
                prevSum -= pstSelf[move.color][move.piece][from[0]][from[1]];
                prevSum += pstSelf[move.color][move.piece][to[0]][to[1]];
            }
        }

        return prevSum;
    }

    /*
     * Performs the minimax algorithm to choose the best move: https://en.wikipedia.org/wiki/Minimax (pseudocode provided)
     * Recursively explores all possible moves up to a given depth, and evaluates the game board at the leaves.
     *
     * Basic idea: maximize the minimum value of the position resulting from the opponent's possible following moves.
     * Optimization: alpha-beta pruning: https://en.wikipedia.org/wiki/Alpha%E2%80%93beta_pruning (pseudocode provided)
     *
     * Inputs:
     *  - game:                 the game object.
     *  - depth:                the depth of the recursive tree of all possible moves (i.e. height limit).
     *  - isMaximizingPlayer:   true if the current layer is maximizing, false otherwise.
     *  - sum:                  the sum (evaluation) so far at the current layer.
     *  - color:                the color of the current player.
     *
     * Output:
     *  the best move at the root of the current subtree.
     */
    function minimax(game, depth, alpha, beta, isMaximizingPlayer, sum, color) {
        var children = game.ugly_moves({verbose: true});

        // Sort moves randomly, so the same move isn't always picked on ties
        children.sort(function (a, b) {
            return 0.5 - Math.random();
        });

        var currMove;
        // Maximum depth exceeded or node is a terminal node (no children)
        if (depth === 0 || children.length === 0) {
            return [null, sum];
        }

        // Find maximum/minimum from list of 'children' (possible moves)
        var maxValue = Number.NEGATIVE_INFINITY;
        var minValue = Number.POSITIVE_INFINITY;
        var bestMove;
        for (var i = 0; i < children.length; i++) {
            currMove = children[i];

            // Note: in our case, the 'children' are simply modified game states
            var currPrettyMove = game.ugly_move(currMove);
            var newSum = evaluateBoard(game, currPrettyMove, sum, color);
            var [childBestMove, childValue] = minimax(
                game,
                depth - 1,
                alpha,
                beta,
                !isMaximizingPlayer,
                newSum,
                color
            );

            game.undo();

            if (isMaximizingPlayer) {
                if (childValue > maxValue) {
                    maxValue = childValue;
                    bestMove = currPrettyMove;
                }
                if (childValue > alpha) {
                    alpha = childValue;
                }
            } else {
                if (childValue < minValue) {
                    minValue = childValue;
                    bestMove = currPrettyMove;
                }
                if (childValue < beta) {
                    beta = childValue;
                }
            }

            // Alpha-beta pruning
            if (alpha >= beta) {
                break;
            }
        }

        if (isMaximizingPlayer) {
            return [bestMove, maxValue];
        } else {
            return [bestMove, minValue];
        }
    }

    /*
     * Calculates the best legal move for the given color.
     */
    function getBestMove(game, color, currSum) {
        var [bestMove, bestMoveValue] = minimax(
            game,
            depth,
            Number.NEGATIVE_INFINITY,
            Number.POSITIVE_INFINITY,
            true,
            currSum,
            color
        );

        console.log('bestMove', bestMove);
        return [bestMove, bestMoveValue];
    }

    /*
     * Makes the best legal move for the given color.
     */
    function makeBestMove(color) {
        console.log('makeBestMove', color);
        const move = getBestMove(game, color, color === 'b' ? globalSum : -globalSum)[0];

        game.move(move);
        board.position(game.fen());

        globalSum = evaluateBoard(game, move, globalSum, 'b');

        if (color === 'b') {
            updateGame('white');
        } else {
            updateGame('black');
        }
    }

    /*
     * The remaining code is adapted from chessboard.js examples #5000 through #5005:
     * https://chessboardjs.com/examples#5000
     */
    function removeGreySquares() {
        $('#board .square-55d63').css('background', '');
    }

    function greySquare(square) {
        var $square = $('#board .square-' + square);

        var background = whiteSquareGrey;
        if ($square.hasClass('black-3c85d')) {
            background = blackSquareGrey;
        }

        $square.css('background', background);
    }

    function onDragStart(source, piece) {
        // do not pick up pieces if the game is over
        if (game.game_over()) return false;

        // or if it's not that players turn
        if (!isPlayersTurn()) return false;

        // or if it's not that side's turn
        if (
            (game.turn() === 'w' && piece.search(/^b/) !== -1) ||
            (game.turn() === 'b' && piece.search(/^w/) !== -1)
        ) {
            return false;
        }
    }

    function isPlayersTurn() {
        if (playersTurn === null) return true;
        return playersTurn === getPlayerColor(game.turn());
    }

    function getPlayerColor(turn) {
        return turn === 'w' ? 'white' : 'black';
    }

    function onDrop(source, target) {
        undo_stack = [];
        removeGreySquares();

        // see if the move is legal
        var move = game.move({
            from: source,
            to: target,
            promotion: 'q', // NOTE: always promote to a queen for example simplicity
        });

        // Illegal move
        if (move === null) return 'snapback';

        globalSum = evaluateBoard(game, move, globalSum, 'b');
        updateGame();

        if (!checkStatus()) {
            // Make the best move for black
            window.setTimeout(() => makeBestMove('b'), 250);
        }
    }

    function onMouseoverSquare(square, piece) {
        // get list of possible moves for this square
        var moves = game.moves({
            square: square,
            verbose: true,
        });

        // exit if there are no moves or if it is not that players turn
        if (moves.length === 0 || !isPlayersTurn()) return;

        // highlight the square they moused over
        greySquare(square);

        // highlight the possible squares for this piece
        for (var i = 0; i < moves.length; i++) {
            greySquare(moves[i].to);
        }
    }

    function onMouseoutSquare(square, piece) {
        removeGreySquares();
    }

    function onSnapEnd() {
        board.position(game.fen());
    }
});
