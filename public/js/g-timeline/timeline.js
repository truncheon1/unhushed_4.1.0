function showAnswer(id){
	if(document.getElementById(id).style.display=="none"){
		document.getElementById(id).style.display="";
	}else{
		document.getElementById(id).style.display="none";
	}
}

function hideAll(){
	for(i=0;i<list.length;i++){
		document.getElementById(list[i]).style.display="none";
	}
}

$(function(){
	$("#answers").hide("fast");
	$("#answersToggle").click(function(){
		$("#answers").toggle("slow");
	});
	$("#answer1").hide("fast");
	$("#answer2").hide("fast");
	$("#answer1Toggle").click(function(){
		$("#answer1").toggle("slow");
		$("#answer2").hide("fast");
	});
	$("#answer2Toggle").click(function(){
		$("#answer2").toggle("slow");
		$("#answer1").hide("fast");
	});
});

$( init );

function init() {
        $('#answerBoxes').sortable();
//	$('#makeDraggable').draggable();
//	$('#draggable').draggable( {
//		cursor: 'move',
//		containment: 'document',
//		helper: helper
//	} );
}

function helper( event ) {
  return '<div id="helper" style="font-size:.6em;">drag helper</div>';
}

var correctCards = 0;

$( gameInit );
// answers

var numbers = [ 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16 ];
var text = [
	'knows gender assigned at birth',
	'makes and adheres to gender stereotypes',
	'first self reported porn viewing',
	'first period',
	'first self reported masturbation (penile)',
	'first self reported ejaculation (penile)',
	'first self reported kiss',
	'first self reported falling in love',
	'first self reported penile/vaginal intercourse',
	'first self reported masturbation (vulva/vagina)',
	'first self reported committed relationship',
	'first self reported coming out as LGBTQ+',
	'first birth',
	'first marriage',
	'first divorce',
	'menopause'
];


function gameInit() {
	// Hide the success message
	$('#successMessage').hide();
	$('#successMessage').css( {
	} );
	$('#message').hide();
	$('#message').css( {
	} );
        $("#checkAnswers").show();
        numbers.sort( function() { return Math.random() - .5 } );
	// Reset the game
	correctCards = 0;
	$('#answerBoxes').html( '' );
	$('#ageBoxes').html( '' );
	var numOfCards=16;



	for ( var i=0; i<text.length; i++ ) {
		$('<div>' + text[numbers[i]-1] + '</div>').data( 'number', numbers[i] ).attr( 'id', 'card'+numbers[i] ).appendTo( '#answerBoxes' );//.draggable( {
//			containment: '#content',
//			stack: '#answerBoxes div',
//			cursor: 'move',
//			revert: true,
//			start:hideMessage
//		} );
	}

	// Ages
	var words = [
		'2 years old',
		'3 yrs old',
		'10 yrs old',
		'12 yrs old',
		'14 yrs old',
		'14 yrs old',
		'15 yrs old',
		'16.5 yrs old',
		'17 yrs old',
		'17 yrs old',
		'18 yrs old',
		'20 yrs old',
		'26 yrs old',
		'27 yrs old',
		'31-32 yrs old',
		'51 yrs old',
	];
	for ( var i=1; i<=words.length; i++ ) {
		$('<div>' + words[i-1] + '</div>').data( 'number', i ).appendTo( '#ageBoxes' );//.droppable( {
//			accept: '#answerBoxes div',
//			hoverClass: 'hovered',
//			drop: handleCardDrop
//		} );
	}
}

var _orderDropped = [];

function getSubmited(){
	res = verifySubmission();
        console.log(res);
        $('.fcount').text(res);
        $('#checkAnswers').hide();
        $('#successMessage').show();
        $('#successMessage').animate( {
                opacity: 1
        } );
	
}

function verifySubmission(){
	let success = true;
	let reason = '';
        sorted = [];
        $("#answerBoxes > div").each(function(){
            sorted.push($(this).data('number'));
        });
        return countCorrectAnswers(sorted);
}

function countCorrectAnswers(list){
    let variants = {
        5: [5,6],
        6: [6,5],
        9: [9,10],
        10: [9,10]
    }
    countCorrect = 0;
    for(x = 0; x < list.length; x++){
        key = x+1;
        if(list[x] == key){
            console.log(key, list[x]);
            countCorrect++;
        }else if(typeof variants[key] != "undefined"){
            
                for(k in variants[key]){
                    if(variants[key][k] == list[x]){
                        console.log(variants[key][k], list[x]);
                        countCorrect++;
                    }else{
                        console.log(variants[key][k], "<>", list[x]);
                    }
                        
                }
            
        }else{
            console.log(key, "<>", list[x]);
        }
    }
    return countCorrect;
}


function pushAnswer(slotNumber, cardNumber){
	modified = false;
	for(x = 0; x < _orderDropped.length; x++){
		if(_orderDropped[x].slotNumber == slotNumber){
			modified = true;
			_orderDropped[x].cardNumber = cardNumber;
		}
	}
	if(!modified){
		_orderDropped.push(
			{
				'slotNumber': slotNumber,
				'cardNumber': cardNumber
			}
		)
	}
	console.log(modified);
}


function handleCardDrop( event, ui ) {
	var slotNumber = $(this).data( 'number' );
	var cardNumber = ui.draggable.data( 'number' );
	pushAnswer(slotNumber, cardNumber);
	// Check and count if dropped in correct slot.
	//if ( slotNumber == cardNumber ) {
		animateMessage();
		ui.draggable.addClass( 'correct' );
		// ui.draggable.draggable( 'disable' );
		// $(this).droppable( 'disable' );
		ui.draggable.position( { of: $(this), my: 'left top', at: 'left top' } );
		ui.draggable.draggable( 'option', 'revert', false );
		correctCards++;
	//}

	// Once all cards are correctly dropped display message and restart button.
	if ( correctCards == 16 ) {
		$('#successMessage').show();
		$('#successMessage').animate( {
			opacity: 1
		} );
	}
}

function hideMessage(){
	$('#message').animate( {
		opacity: 0
	});
}

function animateMessage(){
	$('#message').animate( {
		opacity: 1
	});
}
