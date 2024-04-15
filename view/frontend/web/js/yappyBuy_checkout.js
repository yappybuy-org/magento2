define(
    [
        'jquery',
        'mage/url',
        'mage/storage',        
        'mage/translate',
		'Magento_Customer/js/customer-data',
		'domReady!'
    ],
    function ($, urlBuilder,  storage, $t, customerData) {
		

		$.widget('mage.yappybuyCheckout', {
			options: {},		
			ybObserver: null,
			popupWindow: null,
			yappiPopupWin: null,	
			isClosedTimer: null,		
			inactivityTimer: null,
			popupUrl: null,
			popupButtonYB: null,
			buttonPressed:false,
			
			_create: function () {								
				if(window.location.href.includes('checkout/onepage/success')){
					var sections = ['cart'];
					customerData.invalidate(sections);
					customerData.reload(sections, true);	
				}
				this._getData(urlBuilder.build('yb_checkout/checkout/ConfigAction'),this._setOptions.bind(this));				
			},
			_setOptions: function (response){
				this.options = response;				
											
				this.ybObserver = new MutationObserver(this._searchForPopUpButton.bind(this));
				const observEl = document.querySelector('body') || false;
				if(observEl){
					this.ybObserver.observe(observEl, {childList: true, subtree: true});
				}
				this._addHtml();
				
				this._initPopUpButton();
							
			},
			
			_initPopUpButton(){
				const popupButton = document.querySelector(this.options.popUpButtonSelector) || false;
				const existingYbButton = document.querySelector('#yappyBuyMinicart') || false;
				
				if(this.popupButtonYB==null && popupButton && !existingYbButton){
					const isPopupButtonDisabled = popupButton.classList.contains('disabled');
					
					this.popupButtonYB=document.createElement('div');
					this.popupButtonYB.setAttribute('id', 'yappyBuyMinicart');					
					this.popupButtonYB.innerHTML = this.options.button;
					popupButton.insertAdjacentElement('afterend', this.popupButtonYB);
					if(this.options.disableMagentoButton==1){
						popupButton.style.display = 'none';
					}else{
						this.popupButtonYB.style.marginTop = '10px';
					}	
					this.popupButtonYB.addEventListener('click', this._activateCheckout.bind(this)); 			
				} 
			},			
			_addHtml: function (){				
				if(this.options.mode=='popup'){
					this.popupWindow=document.createElement('div');
					this.popupWindow.setAttribute('class', 'yb-popup-bg');
					this.popupWindow.style.display = 'none';					
					this.popupWindow.innerHTML = 
						'<div  id="yappyBuy-popup" class="content popup-init" ><a style="color:#fff;" href="javascript:void(0);">'+this.options.activateLabel+'</a></div>';
					document.body.appendChild(this.popupWindow);
				}		
				const cartButton = document.querySelector(this.options.cartButtonSelector) || false;
				if(cartButton){
					cartButtonYB=document.createElement('div');
					cartButtonYB.setAttribute('id', 'yappybuy-checkout-button-cart');
					cartButtonYB.setAttribute('class', 'yappybuy-request-button cart');
					cartButtonYB.innerHTML = this.options.button;
					cartButton.insertAdjacentElement('afterend', cartButtonYB);
					
					if(this.options.disableMagentoButton==1){
						cartButton.style.display = 'none';						
					}else{
						cartButtonYB.style.marginTop = '10px';
					}				
					cartButtonYB.addEventListener('click', this._activateCheckout.bind(this));
				}
				if(this.options.showQuickButton){
					const addToCartButton = document.querySelector(this.options.addToCartButtonSelector) || false;
					if(addToCartButton){
						quickButtonYB=document.createElement('div');
						quickButtonYB.setAttribute('id', 'yappybuy-quick-add-button');
						quickButtonYB.setAttribute('class', 'action  quickBuy');
						quickButtonYB.innerHTML = this.options.quickButton;
						addToCartButton.insertAdjacentElement('afterend', quickButtonYB);
				
						quickButtonYB.addEventListener('click', this._activateCheckout.bind(this));
					}
				}

			},		
			_addAndActivateCheckout: function(){
				
				var form = $('#product_addtocart_form');
				if (!(form.validation() && form.validation('isValid'))) {
						return;
				}
				
				this.ybObserver.disconnect(); 				

				$.ajax({
					url: urlBuilder.build('yb_checkout/checkout/Quick/mode/'+this.options.mode),
					data: form.serialize(),
					type: 'post',
					dataType: 'json',

					/** Show loader before send */
					beforeSend: function () {
							$('body').trigger('processStart');
					}
				}).always(function () {
						$('body').trigger('processStop');
				}).done(function( data ) {					
					console.log( data );				
					var sections = ['cart'];
					customerData.invalidate(sections);
					customerData.reload(sections, true);	

				}); 

				
			},			
			_activateCheckout: function(event){
				console.log(event);	

				if(isAddAndCall=!!event.target.closest('#yappybuy-quick-add-button')){
					var form = $('#product_addtocart_form');
					if (!(form.validation() && form.validation('isValid'))) {
							return;
					}
				}
				if(!this.buttonPressed){
					this.buttonPressed=true;					
					$('body').trigger('processStart'); 
					if(this.options.mode=="newtab"){
						this.yappiPopupWin=window.open('', '_blank');
					}else if(this.options.mode=="popup"){
						this.popupWindow.style.display = 'block';
						this.popupWidth=screen.availWidth>1225?980:screen.availWidth * 0.8;
						this.popupLeft=(screen.availWidth-this.popupWidth)/2;					
						
						this.yappiPopupWin=window.open('',
							'yappy-1',
							'toolbar=no,location=no,status=no,menubar=no,scrollbars=no,resizable=yes,width='+(this.popupWidth)+',height='+(screen.availHeight * 0.8)+',top='+(screen.availHeight * 0.1)+',left='+(this.popupLeft)
						); 						
						
					}
					if(isAddAndCall){
						this._getData(urlBuilder.build('yb_checkout/checkout/Quick/mode/'+this.options.mode+'?ajax=1&'+ form.serialize()),this._showCheckout.bind(this),'POST',undefined, isAddAndCall);
					}else{
						this._getData(urlBuilder.build('yb_checkout/checkout/CheckoutAction/mode/'+this.options.mode),this._showCheckout.bind(this),undefined, undefined,isAddAndCall);
					}
				}
				this.ybObserver.disconnect(); 

			},
			_ifCheckoutClosed(){
				if(this.yappiPopupWin!=null && this.yappiPopupWin.closed) {
					clearInterval(this.isClosedTimer);
					window.focus();
					document.location.reload();
				}
			},				
			_reActivateCheckoutWin(){	
				this.yappiPopupWin.focus();				
			},			
			_showCheckout(response, isAddAndCall){		
				this.buttonPressed=false;
				$('body').trigger('processStart'); 
				if(response.status=='success'){
					this.popupUrl=response.url;	
					if(this.options.mode=="newtab"){
						this.yappiPopupWin.location.href=response.url;
					}else if(this.options.mode=="popup"){
						this.isClosedTimer = setInterval(this._ifCheckoutClosed.bind(this), 1000);
						document.querySelector('#yappyBuy-popup a').addEventListener('click', this._reActivateCheckoutWin.bind(this)); 
						this.yappiPopupWin.location.href=response.url;	
					}else{
						document.location.href=response.url;
					}
					
				}else{
					this.popupWindow.style.display = 'none';
					if(this.yappiPopupWin!=null){
						this.yappiPopupWin.close();
					}
					alert(response.message);
				}		
			},			 			
			
			_searchForPopUpButton: function (record){
				for(let mutation of record) {
					for(let node of mutation.addedNodes){				
						if (!(node instanceof HTMLElement)) continue;				
						for(let elem of node.querySelectorAll(this.options.popUpButtonSelector)) {
							this._initPopUpButton();
							this.ybObserver.disconnect();
							break;
						}				
					}
						
				}
			},
			_getData(url,callBack,method='GET', params=null, ...extraArguments) {
				const xhr = new XMLHttpRequest();
				

				xhr.open(method, url);		
				
				xhr.addEventListener('load', () => {
					const response = JSON.parse(xhr.responseText);
					callBack(response, ...extraArguments);
				});

				xhr.addEventListener('error', () => {
					console.log('error');
				});

				xhr.send();
			}
		});

		return $.mage.yappybuyCheckout;    

		
    }
);

