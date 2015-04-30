$('body').append('<div class=modal-bg style=display:none;></div>');

OpenSprites.modals = {};
OpenSprites.modal = function(uniqueName, contents, submit, buttons) {
    if(typeof uniqueName == 'object') {
        buttons = uniqueName.buttons || {submit: 'Submit', cancel: 'Cancel'};
        submit = uniqueName.submit || false;
        contents = uniqueName.content || uniqueName.contents;
        uniqueName = uniqueName.name || uniqueName.uniqueName;
    }
    
    if(typeof uniqueName == 'undefined') {
        return console.error('TypeError: OpenSprites.modal expects at least 1 parameter.');
    }
    
    this.buttons = buttons || {submit: 'Submit', cancel: 'Cancel'};
    this.submit = submit || void(0);
    contents = contents || '<h1>'+uniqueName+'</h1>';
    
    contents += '<br><div class=buttons-container><button class="dialog-button cancel">'+buttons.cancel+'</button><button class="dialog-buttom primary-button ok">'+buttons.submit+'</button>';
    
    $('body').append('<div class="modal" id="modal-'+uniqueName+'" style="display:none;"><div class="modal-content">'+contents+'</div></div>');
    
    this.modal = '#modal-'+uniqueName;
    this.content = this.modal+' .modal-content';

    this.show = function() {
        $(this.modal + ', .modal-bg').fadeIn();
    };
    
    this.hide = function() {
        $(this.modal + ', .modal-bg').fadeOut();
    };
    
    OpenSprites.modals[uniqueName] = this;
    
    $(this.modal).find('*').each(function() {
        $(this).attr('data-modal', uniqueName);    
    });
    
    $(this.content+' .cancel').click(function() {
        OpenSprites.modals[$(this).attr('data-modal')].hide();
    });
    
    $(this.content+' .ok').click(function() {
        OpenSprites.modals[$(this).attr('data-modal')].submit(OpenSprites.modals[$(this).attr('data-modal')]);
    });
    
    return null;
};
