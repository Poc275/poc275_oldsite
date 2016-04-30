var lastFocusedInputElement;

function verbChanged() {

	var inputs = document.getElementsByTagName("input");
	var iTags = document.getElementsByClassName("fa");
	var spans = document.getElementsByTagName("span");

	for (var i = 0; i < inputs.length; i++) {

		inputs[i].value = "";
	}

	while (iTags.length > 0) {

		if (iTags[0].parentNode) {

			iTags[0].parentNode.removeChild(iTags[0]);
		}
	}

	while (spans.length > 0) {

		if (spans[0].parentNode) {

			spans[0].parentNode.removeChild(spans[0]);
		}
	}
}


function latinCharacterClick(char) {

	if (lastFocusedInputElement != null) {
		lastFocusedInputElement.value += char;	
	}
}


function inputFocus(element) {

	lastFocusedInputElement = element;
}