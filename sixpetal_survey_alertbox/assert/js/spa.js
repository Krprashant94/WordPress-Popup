home_url = "https://mlenderz.com";
home_page = 'mlenderz.com';
spa_question_tree = -1;
current_page = '';
makepopup = false;
spa_last_page = 0;
url = window.location.href.split("/");
logger =false;
question_count = 0;
tmp_selected = 0;

for(var i =0; i<url.length; i++){
	if(logger){
		if(url[i] != '')
			current_page = current_page+'/'+url[i];
	}
	if(url[i] == home_page){
		logger = true;
	}
}
if (current_page=='') {
	current_page = '/home';
}
console.log("SPA current page : "+current_page);

if(!makepopup){
	createPopup();
}

jQuery('.spa_answer_box').html('<center><div class="spa_answer_box"><img src="wp-content/plugins/sixpetal_survey_alertbox/assert/image/loader.svg"></div></center>');

jQuery.ajax({
	type: "GET",
	url: "wp-content/plugins/sixpetal_survey_alertbox/inc/question_post.php",
	data: 'head='+current_page,
	dataType : "json",
	success: function(json)
	{
		// console.log(json[0]);
		if (json.length ==0) {
			__exit();
		}else{
			spa_question_tree = json[0].tree;
			spa_last_page = json[0].last;

			var option_list = JSON.parse(json[0].option_list);
			q1 = makeHead(
				spa_question_tree,
				json[0].question,
				option_list
			);
			jQuery('.spa_answer_box').html(q1);
		}
	}
});


jQuery(window).bind('keydown', function(e){
	if(e.keyCode === 27){
		popup_close();
	}
});

function setCookie(cname, cvalue, exdays) {
	var d = new Date();
	d.setTime(d.getTime() + (exdays*24*60*60*1000));
	var expires = "expires="+ d.toUTCString();
	document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}

function createPopup(){
	try {
		var already_popup = getCookie('spacu_already_popup');
		if(already_popup == '' || already_popup == undefined || already_popup == null){
			jQuery('.spa_cotainer').addClass('spa_popup');
			jQuery('body').addClass('spa_popup_scrall_off');
			document.body.scrollTop = 0; // For Safari
			document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
		}else{
			// __exit();
		}
	}
	catch(err) {
		jQuery('.spa_cotainer').addClass('spa_popup');
		jQuery('body').addClass('spa_popup_scrall_off');
		document.body.scrollTop = 0; // For Safari
		document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
	}
}
function __exit() {
	jQuery('.spa_cotainer').hide();
	jQuery('.spa_cotainer').removeClass('spa_popup');
	jQuery('body').removeClass('spa_popup_scrall_off');
}
function __continue(tmp) {
	jQuery('.spa_cotainer').html(tmp);
}
function popup_close(){
	tmp = jQuery('.spa_cotainer').html();
	jQuery('.spa_cotainer').html(tmp+'<div class="exit-msg open"><div class="middle"><div class="icon"></div><b> You have almost reached. <br> <small>Few steps to complete</small> </b><div class="btn-group"><a class="btn flat-gray large" id="exit" tabindex="0" onclick="__exit();">Exit</a> <a class="btn primary large" id="continue" tabindex="0" onclick="__continue(tmp);">Continue</a></div></div></div>');
}
function setSelected(quesID, option_clicked, option_value){
	// console.log(spa_question_tree);
	// console.log("Qusetion ID : "+ quesID +"\nAnswer:" + option_clicked+"\n Fill : "+option_value);
	if (option_value != undefined) {
		if (quesID == 0) {
			tmp_selected = '0-';
		}else{
			tmp_selected = quesID+'0-';
		}
		setCookie('spa_'+quesID+"_"+option_clicked, option_value, 1);
	}else{
		if (quesID == 0) {
			tmp_selected = option_clicked+'-';
		}else{
			tmp_selected = quesID+option_clicked+'-';
		}
		setCookie('spadata_'+quesID, option_clicked, 1);
		setCookie('spacu_option_clicked', option_clicked, 1);
		// console.log("Qusetion: "+ cu_ques +"\nAnswer:" + cu_ans);
	}
}
function clear_error(e) {
	e.setAttribute("style", "background: rgba(229, 57, 53, 0);");
}
function next_question(e, f) {
	e.preventDefault();
	for(var i =0; i<f.elements.length; i++){
		// console.log(f.elements[i].value);
		if(f.elements[i].getAttribute('validrule') != undefined ){
			var rule = f.elements[i].getAttribute('validrule');
			if (rule != 'undefined' && rule != undefined && rule != null && rule != '') {
				var str = f.elements[i].value; 
				var regex = new RegExp( rule, 'g' );
				var res = str.match(regex);
				if (res == undefined || res == null || res == '') {
					f.elements[i].setAttribute("style", "background: rgba(229, 57, 53, 0.2);")
					setTimeout(clear_error, 1000, f.elements[i])
					return 0;
				}
			}else{
				// console.log("f.elements["+i+"] : have No validation rule.");
			}

		}
	}
	if(!makepopup){
		createPopup();
	}
	if (spa_last_page == 1) {
		spa_question_tree = tmp_selected;
		window.location = "https://mlenderz.com/wp-content/plugins/sixpetal_survey_alertbox/inc/user_post.php";
		// alert("Callback not definded...\nAll data saved in cookie...");
		// __exit();
	}else{
		cu_ques = getCookie('spacu_option_clicked');
		if (cu_ques == undefined || cu_ques == null || cu_ques == '') {
			alert("Please chose one choice.");
		}else {
			spa_question_tree = tmp_selected;
			// console.log("Tree: "+spa_question_tree);
			
			jQuery('.spa_answer_box').html('<center><div class="spa_answer_box"><img src="wp-content/plugins/sixpetal_survey_alertbox/assert/image/loader.svg"></div></center>');
			jQuery.ajax({
				type: "GET",
				url: "wp-content/plugins/sixpetal_survey_alertbox/inc/question_post.php",
				data: 'tree='+spa_question_tree,
				dataType : "json",
				success: function(json)
				{
					// console.log(json);
					if(json.length!=0){
						question_count ++;

						spa_question_tree = json[0].tree;
						spa_last_page = json[0].last;

						var option_list = JSON.parse(json[0].option_list);
						if(json.length == 0){
							alert("Server Side Error !!! \nNo SubProduct is defined for this product. Please add it in '/admin.php?page=spa_settings' at 'Add New Product' Column.");
						}else{
							q1 = makeHead(
								spa_question_tree,
								json[0].question,
								option_list
							);
							jQuery('.spa_answer_box').html(q1);
						}
					}else{
						__exit();
					}
					
				}
			});
		}
	}
}

function prev_question(e, f) {
	// console.log("Tree: "+spa_question_tree);
	tree = spa_question_tree.split("-");
	back_tree = '';
	for (var i = 0; i < tree.length-2; i++) {
		if (tree[i]!='') {
			back_tree += tree[i]+'-';
		}
	}
	if (back_tree=='') {back_tree='0';}
	// console.log('question_count : '+question_count);
	// console.log('back Tree : '+back_tree);
	jQuery('.spa_answer_box').html('<center><div class="spa_answer_box"><img src="wp-content/plugins/sixpetal_survey_alertbox/assert/image/loader.svg"></div></center>');
	
	jQuery.ajax({
		type: "GET",
		url: "wp-content/plugins/sixpetal_survey_alertbox/inc/question_post.php",
		data: 'tree='+back_tree,
		dataType : "json",
		success: function(json)
		{
			// console.log(json);
			
			question_count -=1;

			spa_question_tree = json[0].tree;
			spa_last_page = json[0].last;

			var option_list = JSON.parse(json[0].option_list);
			if(json.length == 0){
				alert("Server Side Error !!! \nNo SubProduct is defined for this product. Please add it in '/admin.php?page=spa_settings' at 'Add New Product' Column.");
			}else{
				q1 = makeHead(
					spa_question_tree,
					json[0].question,
					option_list
				);
				jQuery('.spa_answer_box').html(q1);
			}
			
		}
	});
}

function makeHead(id, question, option) {
	html = '<div class="slide'+id+'">\
			<center>\
				<div class="spa_question">\
					'+question+'\
				</div>\
			</center>\
			<div>\
				<ul class="spa_list">';
	for (var i = 1; i <= option.length; i++) {
		if(option[i-1].datatype=="Radio"){
			html+= '<li class="list__item">\
							<input type="radio" class="radio-btn" onclick="setSelected(\''+id+'\','+i+')" name="choise" id="opt-'+i+'" />\
							<label for="opt-'+i+'" class="spa_label">'+option[i-1].value+'</label>\
						</li>';
		}else if(option[i-1].datatype=="Text"){
						html+= '<li class="list__item" style="padding:5px;"><input validrule="'+option[i-1].validrule+'" tabindex="'+i+'" type="text" placeholder="'+option[i-1].value+'" onchange="setSelected(\''+id+'\', '+i+', this.value)"></li>';
		}else if(option[i-1].datatype=="Number"){
			html+= '<li class="list__item" style="padding:5px;"><input validrule="'+option[i-1].validrule+'" tabindex="'+i+'" type="number" placeholder="'+option[i-1].value+'" onchange="setSelected(\''+id+'\', '+i+', this.value)"></li>';
		}else if(option[i-1].datatype=="Email"){
			html+= '<li class="list__item" style="padding:5px;"><input validrule="'+option[i-1].validrule+'" tabindex="'+i+'" type="email" placeholder="'+option[i-1].value+'" onchange="setSelected(\''+id+'\', '+i+', this.value)"></li>';
		}else if(option[i-1].datatype=="Date"){
			html+= '<li class="list__item" style="padding:5px;"><input validrule="'+option[i-1].validrule+'" tabindex="'+i+'" type="date" placeholder="'+option[i-1].value+'" onchange="setSelected(\''+id+'\', '+i+', this.value)"></li>'
		}else{
			html+= '<li class="list__item">\
							<input tabindex="'+i+'" type="radio" class="radio-btn" onclick="setSelected(\''+id+'\','+i+')" name="choise" id="opt-'+i+'" />\
							<label for="opt-'+i+'" class="spa_label">'+option[i-1].value+'</label>\
						</li>';
		}
	}
	if (spa_last_page == 1) {
		html += '	</ul>\
				</div>\
				<div>\
					<button class="spa_button" onclick="prev_question(event, this.form)" tabindex="'+(i+1)+'">Previous</button>\
					<button class="spa_button spa_next" onclick="next_question(event, this.form)" tabindex="'+(i+2)+'">Submit</button>\
				</div>\
			</div>';
	}else if (question_count == 0) {
		html += '	</ul>\
				</div>\
				<div>\
					<center><button class="spa_button" onclick="next_question(event, this.form)" tabindex="'+(i+1)+'">Get Started</button></center>\
				</div>\
			</div>';
	}else{
		html += '	</ul>\
				</div>\
				<div>\
					<button class="spa_button" onclick="prev_question(event, this.form)" tabindex="'+(i+1)+'">Previous</button>\
					<button class="spa_button spa_next" onclick="next_question(event, this.form)" tabindex="'+(i+2)+'">Next</button>\
				</div>\
			</div>';
	}
	return html;
}