( function ( blocks, blockEditor, components, i18n, element, apiFetch ) {
	var el                = element.createElement;
	var __                = i18n.__;
	var useState          = element.useState;
	var useEffect         = element.useEffect;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps     = blockEditor.useBlockProps;
	var PanelBody         = components.PanelBody;
	var SelectControl     = components.SelectControl;
	var ToggleControl     = components.ToggleControl;
	var Notice            = components.Notice;
	var ServerSideRender  = window.wp.serverSideRender;

	blocks.registerBlockType( 'jditc/upcoming-for-calendly', {
		edit: function ( props ) {
			var attributes       = props.attributes;
			var setAttributes    = props.setAttributes;
			var blockProps       = useBlockProps( {
				style: { minHeight: '1.5em' }
			} );
			var eventTypesState  = useState( [] );
			var eventTypes       = eventTypesState[ 0 ];
			var setEventTypes    = eventTypesState[ 1 ];
			var loadingState     = useState( true );
			var isLoading        = loadingState[ 0 ];
			var setIsLoading     = loadingState[ 1 ];

			useEffect( function () {
				var isMounted = true;
				apiFetch( { path: '/upcoming-for-calendly/v1/event-types' } )
					.then( function ( data ) {
						if ( isMounted ) {
							setEventTypes( data );
							setIsLoading( false );
						}
					} )
					.catch( function () {
						if ( isMounted ) {
							setIsLoading( false );
						}
					} );

				return function () {
					isMounted = false;
				};
			}, [] );

			var eventTypeOptions = [ { label: __( 'All Events', 'upcoming-for-calendly' ), value: '' } ].concat( eventTypes );

			// If current event is set but not in the list, add it with a note.
			if ( attributes.event && ! eventTypes.some( function ( opt ) {
				return opt.value === attributes.event;
			} ) ) {
				eventTypeOptions.push( {
					label: attributes.event + ' ' + __( '(no longer exists)', 'upcoming-for-calendly' ),
					value: attributes.event,
				} );
			}

			return [
				el(
					InspectorControls,
					{ key: 'inspector' },
					el(
						PanelBody,
						{ title: __( 'Settings', 'upcoming-for-calendly' ), initialOpen: true },

					el( SelectControl, {
						label:    __( 'Event name filter', 'upcoming-for-calendly' ),
						help:     isLoading ? __( 'Fetching from Calendly…', 'upcoming-for-calendly' ) : __( 'Select an event type, or choose "All Events" to show all.', 'upcoming-for-calendly' ),
						value:    attributes.event,
						options:  eventTypeOptions,
						disabled: isLoading,
						onChange: function ( val ) {
							setAttributes( { event: val } );
						},
					} ),
					attributes.event && ! eventTypes.some( function ( opt ) {
						return opt.value === attributes.event;
					} ) && el( Notice, {
						status: 'warning',
						isDismissible: false,
					}, __( 'This event type no longer exists in Calendly. Please select a different event or "All Events".', 'upcoming-for-calendly' ) ),
					el( ToggleControl, {
							label:    __( 'Show remaining spots', 'upcoming-for-calendly' ),
							checked:  attributes.showSpots,
							onChange: function ( val ) {
								setAttributes( { showSpots: val } );
							},
						} ),
						el( ToggleControl, {
							label:    __( 'Members-only booking links', 'upcoming-for-calendly' ),
							help:     __( 'When enabled, only logged-in users see clickable booking links.', 'upcoming-for-calendly' ),
							checked:  attributes.membersOnlyLinks,
							onChange: function ( val ) {
								setAttributes( { membersOnlyLinks: val } );
							},
						} )
					)
				),
				el(
					'div',
					Object.assign( {}, blockProps, { key: 'preview' } ),
					el( ServerSideRender, {
						block:      'jditc/upcoming-for-calendly',
						attributes: attributes,
					} )
				),
			];
		},
	} );
} )(
	window.wp.blocks,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.i18n,
	window.wp.element,
	window.wp.apiFetch
);
