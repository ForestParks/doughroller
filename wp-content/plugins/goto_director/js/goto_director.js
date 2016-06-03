//
// goto.js
// Copyright 2010 David J. Meppelink. All rights reserved.
//

function goto_add_request()
{
  var data = {
    action: 'goto_add',
    tag: jQuery('#goto_add_form input[name=tag]').val(),
    url: jQuery('#goto_add_form input[name=url]').val()
  };
  jQuery.post(ajaxurl, data, goto_add_response);

  return false;
}

function goto_add_response(data, status, XMLHttpRequest)
{
  alert(data);
  // Reload the page so that we see the new record.
  // This is not very efficient, but it is easy.
  location.reload();
}

TableKit.options.editAjaxURI = ajaxurl + '?action=goto_edit';

function goto_delete_request(id, tag)
{
  var data = {
    action: 'goto_delete',
    id: id,
    tag: tag
  };
  jQuery.post(ajaxurl, data, goto_delete_response);

  return true;
}

function goto_delete_response(data, status, XMLHttpRequest)
{
  alert(data);
  // Reload the page so that we see the new record.
  // This is not very efficient, but it is easy.
  location.reload();
}

jQuery(document).ready(function() {
  jQuery('#goto_add_button').click(goto_add_request);
});

