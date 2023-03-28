const select = document.querySelectorAll('.selectBtn');
const option = document.querySelectorAll('.option');
let index = 1;

select.forEach(a => {
	a.addEventListener('click', b => {
		const next = b.target.nextElementSibling;
		next.classList.toggle('toggle');
		next.style.zIndex = index++;
	})
})
option.forEach(a => {
	a.addEventListener('click', b => {
		b.target.parentElement.classList.remove('toggle');
		
		const parent = b.target.closest('.select').children[0];
		parent.setAttribute('data-type', b.target.getAttribute('data-type'));
		parent.innerText = b.target.innerText;
	})
})

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl)
})

$(function () {
	$("[data-toggle=popover]").popover({
	 html: true,
	 content: function () {
	  var content = $(this).attr("data-popover-content");
	  return $(content).children(".popover-body").html();
	 },
	 title: function () {
	  var title = $(this).attr("data-popover-content");
	  return $(title).children(".popover-heading").html();
	 },
	 content: function () {
		var title = $(this).attr("data-popover-content");
		return $(title).children(".popover-footer").html();
	   }
	});
   });
   

   $(document).ready(function() {

	$('.po-link').popover({
	trigger: 'click',
	html: true,  // must have if HTML is contained in popover

	// get the title and conent
	title: function() {
		return $(this).parent().find('.po-title').html();
	},
	content: function() {
		return $(this).parent().find('.po-body').html();
	},

	container: 'body',
	placement: 'bottom'

	});

});