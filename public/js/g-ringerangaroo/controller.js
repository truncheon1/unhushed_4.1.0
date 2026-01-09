
$(document).ready(function () {

    var questionNumber = 0;
    var questionBank = new Array();
    var stage = "#game1";
    var stage2 = new Object;
    var questionLock = false;
    var numberOfQuestions;
    var score = 0;

    $.getJSON(base_url + '/activity.json', function (data) {

        for (i = 0; i < data.quizlist.length; i++) {
            questionBank[i] = new Array;
            questionBank[i][0] = data.quizlist[i].question;
            questionBank[i][1] = data.quizlist[i].option1;
            questionBank[i][2] = data.quizlist[i].option2;
            questionBank[i][3] = data.quizlist[i].option3;
            questionBank[i][4] = data.quizlist[i].option4;
        }
        numberOfQuestions = [3];

        scrambleDatabase();
        displayQuestion();
    })//gtjson


    function scrambleDatabase() {
        for (i = 0; i < 50; i++) {
            var rnd1 = Math.floor(Math.random() * questionBank.length);
            var rnd2 = Math.floor(Math.random() * questionBank.length);

            var temp = questionBank[rnd1];
            questionBank[rnd1] = questionBank[rnd2];
            questionBank[rnd2] = temp;

        }//i
        console.log(questionBank);

    }//scdb

    function displayQuestion() {
        var rnd = Math.random() * 4;
        rnd = Math.ceil(rnd);
        var q1;
        var q2;
        var q3;
        var q4;

        if (rnd == 1) {
            q1 = questionBank[questionNumber][1];
            q2 = questionBank[questionNumber][2];
            q3 = questionBank[questionNumber][3];
            q4 = questionBank[questionNumber][4];
        }
        if (rnd == 2) {
            q2 = questionBank[questionNumber][1];
            q3 = questionBank[questionNumber][2];
            q4 = questionBank[questionNumber][3];
            q1 = questionBank[questionNumber][4];
        }
        if (rnd == 3) {
            q3 = questionBank[questionNumber][1];
            q4 = questionBank[questionNumber][2];
            q1 = questionBank[questionNumber][3];
            q2 = questionBank[questionNumber][4];
        }
        if (rnd == 4) {
            q4 = questionBank[questionNumber][1];
            q1 = questionBank[questionNumber][2];
            q2 = questionBank[questionNumber][3];
            q3 = questionBank[questionNumber][4];
        }

        $(stage).append('<div class="questionText"> Which sex act is... <br/>' + questionBank[questionNumber][0] + '</div><div id="1" class="option">' + q1 + '</div><div id="2" class="option">' + q2 + '</div><div id="3" class="option">' + q3 + '</div><div id="4" class="option">' + q4 + '</div>');

        $('.option').click(function () {
            if (questionLock == false) {
                questionLock = true;
                //correct answer
                if (this.id == rnd) {
                    $(stage).append('<div class="feedback1">CORRECT</div>');
                    score++;
                }
                //wrong answer
                if (this.id != rnd) {
                    $(stage).append('<div class="feedback2">NOPE</div>');
                }
                setTimeout(function () {
                    changeQuestion()
                }, 1000);
            }
        })
    }//display question


    function changeQuestion() {

        questionNumber++;

        if (stage == "#game1") {
            stage2 = "#game1";
            stage = "#game2";
        } else {
            stage2 = "#game2";
            stage = "#game1";
        }

        if (questionNumber < numberOfQuestions) {
            displayQuestion();
        } else {
            displayFinalSlide();
        }

        $(stage2).animate({"right": "+=960px"}, "slow", function () {
            $(stage2).css('right', '-960px');
            $(stage2).empty();
        });
        $(stage).animate({"right": "+=960px"}, "slow", function () {
            questionLock = false;
        });
    }//change question


    function displayFinalSlide() {
        $(stage).append(
                '<div class="questionText"><br/><br/>You scored ' + score + ' out of ' + numberOfQuestions + '</div><button class="btn btn-secondary btn-replay">REPLAY</button></div>'
                );
    }//display final slide

    $('body').on('click', '.btn-replay', function (e) {
        console.log("REPLAY");
        scrambleDatabase();
        e.preventDefault();
        questionNumber = -1; //since it starts with 0 and the increments before sliding, on reset it needs to start -1
        score = 0;
        changeQuestion();
    })

});//doc ready
