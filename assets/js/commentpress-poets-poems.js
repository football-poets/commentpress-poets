/**
 * CommentPress Football Poets Poem Loader Javascript.
 *
 * Enables a "Load more" button on Poet Profile pages.
 *
 * @package CommentPress_Poets
 */

/**
 * Pass the jQuery shortcut in.
 *
 * @since 1.4
 *
 * @param {Object} $ The jQuery object.
 */
( function( $ ) {

	/**
	 * Create Settings class.
	 *
	 * @since 1.4
	 */
	function Poets_Display_Settings() {

		// Prevent reference collisions.
		var me = this;

		/**
		 * Initialise Settings.
		 *
		 * This method should only be called once.
		 *
		 * @since 1.4
		 */
		this.init = function() {

			// Init localisation.
			me.init_localisation();

			// Init settings.
			me.init_settings();

		};

		/**
		 * Do setup when jQuery reports that the DOM is ready.
		 *
		 * This method should only be called once.
		 *
		 * @since 1.4
		 */
		this.dom_ready = function() {

		};

		// Init localisation array.
		me.localisation = [];

		/**
		 * Init localisation from settings object.
		 *
		 * @since 1.4
		 */
		this.init_localisation = function() {
			if ( 'undefined' !== typeof Poets_Display_Vars ) {
				me.localisation = Poets_Display_Vars.localisation;
			}
		};

		/**
		 * Getter for localisation.
		 *
		 * @since 1.4
		 *
		 * @param {String} identifier The identifier for the desired localisation string.
		 * @return {String} The localised string.
		 */
		this.get_localisation = function(identifier) {
			return me.localisation[ identifier ];
		};

		// Init settings array.
		me.settings = [];

		/**
		 * Init settings from settings object.
		 *
		 * @since 1.4
		 */
		this.init_settings = function() {
			if ( 'undefined' !== typeof Poets_Display_Vars ) {
				me.settings = Poets_Display_Vars.settings;
			}
		};

		/**
		 * Getter for retrieving a setting.
		 *
		 * @since 1.4
		 *
		 * @param {String} The identifier for the desired setting.
		 * @return The value of the setting.
		 */
		this.get_setting = function( identifier ) {
			return me.settings[ identifier ];
		};

	}

	/**
	 * Create Poems class.
	 *
	 * @since 1.4
	 */
	function Poets_Display_Poems() {

		// Prevent reference collisions.
		var me = this;

		/**
		 * Initialise.
		 *
		 * This method should only be called once.
		 *
		 * @since 1.4
		 */
		this.init = function() {};

		/**
		 * Do setup when jQuery reports that the DOM is ready.
		 *
		 * This method should only be called once.
		 *
		 * @since 1.4
		 */
		this.dom_ready = function() {

			// Set up methods.
			me.setup();
			me.listeners();

		};

		/**
		 * Do initial setup.
		 *
		 * This method should only be called once.
		 *
		 * @since 1.4
		 */
		this.setup = function() {};

		/**
		 * Initialise listeners.
		 *
		 * This method should only be called once.
		 *
		 * @since 1.4
		 */
		this.listeners = function() {

			// Unbind first to allow repeated calls to this function.
			$('.load-more > a').off( 'click' );

			/**
			 * Add a click event listener to the "Load more" button.
			 *
			 * @param {Object} event The event object.
			 */
			$('.load-more > a').on( 'click', function( event ) {

				// Define vars.
				var ajax_nonce = $(this).data( 'security' ),
					poet = $(this).data( 'poet' ),
					page = $(this).data( 'page' );

				// Prevent default behaviour.
				if ( event.preventDefault ) {
					event.preventDefault();
				}

				// Show spinner.
				$(this).addClass( 'loading' );

				// Submit request to server.
				me.send( 'poets_poems_load', poet, page, ajax_nonce );

			});

		};

		/**
		 * Send AJAX request.
		 *
		 * @since 1.4
		 *
		 * @param {String} action The AJAX action.
		 * @param {Integer} poet The numeric ID of the Poet.
		 * @param {Integer} poet The numeric ID of the Page.
		 * @param {String} token The AJAX security token.
		 */
		this.send = function( action, poet, page, token ) {

			// Define vars.
			var url, data;

			// URL to post to.
			url = Football_Poets_Display_Settings.get_setting( 'ajax_url' );

			// Data received by WordPress.
			data = {
				action: action,
				poet: poet,
				page: page,
				_ajax_nonce: token
			};

			// Use jQuery post method.
			$.post( url, data,

				/**
				 * AJAX callback which receives response from the server.
				 *
				 * Calls feedback method on success or shows an error in the console.
				 *
				 * @since 1.4
				 *
				 * @param {Mixed} value The value to send.
				 * @param {String} token The AJAX security token.
				 */
				function( data, textStatus ) {
					if ( textStatus == 'success' ) {
						me.feedback( data );
					} else {
						if ( console.log ) {
							console.log( textStatus );
						}
					}
				},

				// Expected format.
				'json'

			);

		};

		/**
		 * Provide feedback given a set of data from the server.
		 *
		 * @since 1.4
		 *
		 * @param {Array} data The data received from the server.
		 */
		this.feedback = function( data ) {

			if ( data.content ) {

				// Hide previous "Load more".
				$('.load-more > a.loading').parent().hide();

				// Append Poem titles.
				$('.poems-by-poet-list').append( $.parseHTML( data.content ) );

				// Refresh listeners.
				me.listeners();

			}

		};

	}

	// Init Settings and Poems classes.
	var Football_Poets_Display_Settings = new Poets_Display_Settings();
	var Football_Poets_Display_Poems = new Poets_Display_Poems();
	Football_Poets_Display_Settings.init();
	Football_Poets_Display_Poems.init();

	/**
	 * Trigger dom_ready methods where necessary.
	 *
	 * @since 1.4
	 *
	 * @param {Object} $ The jQuery object.
	 */
	$( document ).ready( function( $ ) {
		Football_Poets_Display_Settings.dom_ready();
		Football_Poets_Display_Poems.dom_ready();
	});

} )( jQuery );
