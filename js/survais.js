var survai_answerKeys = ['answer_a', 'answer_b', 'answer_c', 'answer_d', 'answer_e'];

function survais_displayData(data){
    var globalSurvais = data.global_survais;
    var singleSurvais = data.single_survais;
    var i,x,y;
    var survai,question;
    var survai_html = '';
    var survaiClass;
    var survaiButtonMethod;
    var survaiButtonClass;
    
    if(globalSurvais.length > 0){
        for(i=0; i<globalSurvais.length; i++){
            survai = globalSurvais[i];
            
            if(survais_active_survai == survai.identifier){
                survaiClass = 'survai active-survai';
                survaiButtonMethod = 'Disable';
                survaiButtonClass = 'disabled';
            } else {
                survaiClass = 'survai';
                survaiButtonMethod = 'Enable';
                survaiButtonClass = '';
            }
            survai_html += '<div class="'+survaiClass+'">';
                survai_html += '<div class="survai-title">';
                    survai_html += survai.nickname
                survai_html += '<button class="enable-btn '+survaiButtonClass+'" survai-identifier="'+survai.identifier+'" survai-embed-code="'+encodeURI(survai.embed_code)+'">'+survaiButtonMethod+'</button>';
                survai_html += '</div>';
            survai_html += '</div>';
        }
    } else { // else 0 global survais present
        survai_html += '<p>You have no active global Survais. Create Survais via the <a href="https://www.survais.com/app" target="_blank">Survais dashboard.</a>';
    }

    jQuery('.global-survais').append(survai_html);
    survai_html ='';

    if(singleSurvais.length > 0){
        for(i=0; i<singleSurvais.length; i++){
            survai = singleSurvais[i];
            
            if(survais_active_survai == survai.identifier){
                survaiClass = 'survai active-survai';
                survaiButtonMethod = 'Disable';
                survaiButtonClass = 'disabled';
            } else {
                survaiClass = 'survai';
                survaiButtonMethod = 'Enable';
                survaiButtonClass = '';
            }
            survai_html += '<div class="'+survaiClass+'">';
                survai_html += '<div class="survai-title">';
                    survai_html += survai.nickname
                survai_html += '<button class="enable-btn '+survaiButtonClass+'" survai-identifier="'+survai.identifier+'" survai-embed-code="'+encodeURI(survai.embed_code)+'">'+survaiButtonMethod+'</button>';
                survai_html += '</div>';
            survai_html += '</div>';
        }
    } else { // else 0 single survais present
        survai_html += '<p>You have no active single Survais. Create Survais via the <a href="https://www.survais.com/app" target="_blank">Survais dashboard.</a>';
    }
    jQuery('.single-survais').append(survai_html);
    survai_html ='';

    jQuery('.loading-view').hide();
    jQuery('.survais-view').show();

    survais_initDynamicListeners();
} // end display data

function survais_saveOptions(identifier, embedCode){
  jQuery.ajax({
    method: 'POST',
    data: {
      action: 'survais_save_options',
      survai_identifier: identifier,
      survai_embed_code: decodeURI(embedCode)
    },
    url: ajaxurl,
    success: function(data){
      if(data == 'updated'){
        location.reload();
      } else {
        alert('Error saving options');
        jQuery('button').attr('disabled', false);
      }
    },
    error: function(xhr, status, error) {
      console.log('Error:', error);
      jQuery('button').attr('disabled', false);
    }
  });
}

function survais_getDataForUser(user){
  jQuery.ajax({
    dataType: 'jsonp',
    jsonp: 'callback',
    url: survais_apiURL,
    data: {
      user: survais_user
    },
    success: function(data){
      if(data.valid_user === true){
        survais_displayData(data);
      } else {
        survais_logUserOut();
        alert('An error occured, and you have been logged out');
      }
    },
    error: function(xhr, status, error) {
      console.log('Error:', error);
      jQuery('button').attr('disabled', false);
    }
  });
}

function survais_logUserOut() {
  jQuery.ajax({
    method: 'POST',
    url: survaisPlugin.pluginsUrl + '/admin/logout.php',
    success: function(data){
      location.reload();
    },
    error: function(xhr, status, error) {
      console.log('Error:', error);
      location.reload();
    }
  });
}

function survais_setupUserSession(user) {
  var me = this;
  jQuery.ajax({
    method: 'POST',
    url: this.survaisPlugin.pluginsUrl + '/inc/setup-session.php',
    data: {
      user: user
    },
    success: function(data){
      console.log('session: ', data)
      location.reload();
    },
    error: function(xhr, status, error) {
      console.log('Error:', error);
      jQuery('button').attr('disabled', false);
    }
  });
}

function survais_signUserIn(e){
  e.stopPropagation();

  jQuery('button').attr('disabled', true);
  var email = jQuery('#email').val();
  var password = jQuery('#password').val();
  var confirmPassword = jQuery('#signupConfirmPassword').val();
  var name = jQuery('#name').val();

  if(this.id == 'signUpBtn'){
    console.log('signing up');
    email = jQuery('#signupEmail').val();
    password = jQuery('#signupPassword').val();
    if(password !== confirmPassword){
      alert('Passwords do not match');
      jQuery('button').attr('disabled', false);
      return false;
    }
  }

  jQuery.ajax({
    dataType: 'jsonp',
    jsonp: 'callback',
    url: survais_loginURL,
    data: {
      email: email,
      password: password
    },
    success: function(data){
      if(data['msg'] === 'incorrect-combination'){
        alert('Wrong email/password combination, or user does not exist.');
        jQuery('button').attr('disabled', false);
      } else if(data['msg'] === 'error'){
        alert('An error has occurred');
        jQuery('button').attr('disabled', false);
      } else if(data['msg'] === 'logged-in'){
        survais_setupUserSession(data['user']);
      }
    },
    error: function(xhr, status, error) {
      console.log('Error:', error);
      jQuery('button').attr('disabled', false);
    }
  });
}

function survais_expandSurvai(e){
  if(e.target.classList.toString().indexOf('survai-title') > -1){
    jQuery(this).find('.survai-questions').toggle();
  }
}

function survais_toggleSurvai(e){
  var identifier;
  var embedCode;

  if(e.target.innerHTML === 'Disable'){
    identifier = '';
    embedCode = '';
  } else {
    identifier = jQuery(this).attr('survai-identifier');
    embedCode = jQuery(this).attr('survai-embed-code');
  }

  jQuery('button').attr('disabled', true);
  survais_saveOptions(identifier, embedCode)
}

function survais_initDynamicListeners(){
  jQuery('.survai').off('click').on('click', survais_expandSurvai);
  jQuery('.enable-btn').off('click').on('click', survais_toggleSurvai);
}

jQuery(document).ready(function(){
  console.log('Survais WordPress Initialized');
  jQuery('#signInBtn, #signUpBtn').on('click', survais_signUserIn);
  jQuery('.logout-link').on('click', survais_logUserOut);
});
