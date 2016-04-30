var articleThumbnails;
var filter = "";


function init() {
	articleThumbnails = document.getElementById("main").getElementsByTagName("article");
}


function filterLinkClicked(filterBy) {

	if (filter == "") {
		// first time filter
		filter = filterBy;
		filterArticle();
		styleFilterLinks();
	} else if (filter != filterBy) {
		// a new filter is being applied, reset all and refilter
		filter = filterBy;
		resetArticleDisplay();
		filterArticle();
		styleFilterLinks();
	} else {
		// filter has been clicked twice - treat this as a reset
		resetArticleDisplay();
		resetFilterLinksStyles();
		filter = "";
	}
}


function filterArticle() {

	for (var i = 0; i < articleThumbnails.length; i++) {
			
		var article = articleThumbnails[i];

		if (article.dataset.lang != filter) {
			article.style.display = "none";
		}
	}
}


function resetArticleDisplay() {
	for (var i = 0; i < articleThumbnails.length; i++) {
		articleThumbnails[i].style.display = "inline-block";
	}
}


function styleFilterLinks() {

	var filterLinks = document.getElementById("filters").getElementsByTagName("a");

	for (var i = 0; i < filterLinks.length; i++) {

		if (filterLinks[i].id == filter) {
			filterLinks[i].style.backgroundColor = "#ccc";
		} else {
			filterLinks[i].style.backgroundColor = "transparent";
		}
	}
}


function resetFilterLinksStyles() {
	var filterLinks = document.getElementById("filters").getElementsByTagName("a");

	for (var i = 0; i < filterLinks.length; i++) {
		filterLinks[i].style.backgroundColor = "transparent";
	}
}