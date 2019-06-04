# Changelog

## 1.19.0 (March 28, 2019)

### Added

- Add help system
- Add help videos for layout and module panels
- Add page builder support for blog posts
- Add option show the post excerpt in the blog overview (WordPress)
- Add image options for boxed page layout
- Add option to display the header outside of the boxed page layout
- Add option to let the toolbar inherit the transparency from the navbar
- Add grid options for title alignment in Panel, Grid and Switcher elements
- Add option to align meta text below the content to elements
- Add link type options to Panel, Grid and Popover elements
- Add panel padding option to Panel and Grid elements
- Add link options to Overlay, Gallery and Slider elements
- Add primary navbar style

### Changed

- Allow to set top and bottom margin independently for the boxed page layout
- Improve links for search engines and accessibility in Panel, Grid, Popover, Overlay, Gallery and Slider elements
- Change default page container width to 1500px
- Allow navbar to have different top and bottom paddings

### Fixed

- Fix headerbar-bottom color if transparent in `Stacked Center B` layout
- Fix background image size for external images
- Fix image link missing target attribute in List element
- Fix duplicate items in Lightbox
- Fix overlay is shown empty in Overlay, Gallery and Slider element
- Fix scroll position when searching the Unsplash library
- Fix rare case of broken header in Chrome

## 1.19.0-beta.3 (March 27, 2019)

### Added

- Add page builder support for blog posts
- Add option show the post excerpt in the blog overview (WordPress)

### Fixed

- Fix rare case of broken header in Chrome

## 1.19.0-beta.2 (March 25, 2019)

### Added

- Add primary navbar style

### Changed

- Change default page container width to 1500px
- Allow navbar to have different top and bottom paddings

### Fixed

- Fix background image size for external images
- Fix image link missing target attribute in List element
- Fix duplicate items in Lightbox
- Fix overlay is shown empty in Overlay, Gallery and Slider element
- Fix scroll position when searching the Unsplash library

## 1.19.0-beta.1 (March 19, 2019)

### Added

- Add help system
- Add help videos for layout and module panels
- Add image options for boxed page layout
- Add option to display the header outside of the boxed page layout
- Add option to let the toolbar inherit the transparency from the navbar
- Add grid options for title alignment in Panel, Grid and Switcher elements
- Add option to align meta text below the content to elements
- Add link type options to Panel, Grid and Popover elements
- Add panel padding option to Panel and Grid elements
- Add link options to Overlay, Gallery and Slider elements

### Changed

- Allow to set top and bottom margin independently for the boxed page layout
- Improve links for search engines and accessibility in Panel, Grid, Popover, Overlay, Gallery and Slider elements

### Fixed

- Fix headerbar-bottom color if transparent in `Stacked Center B` layout

## 1.18.16 (March 19, 2019)

### Fixed

- Fix missing map to add marker in Map element
- Fix preview being displayed in previous state, when entering style component
- Allow `Filter All Label` translation in Grid elements

## 1.18.15 (March 07, 2019)

### Fixed

- Allow WebP image format in media manager
- Fix `Add From Media` items missing images in Popover element
- Fix set `target` and `rel` attribute in menu item anchor (Joomla)
- Fix post gallery missing image caption (WordPress)
- Fix SVG support in IE11/Edge

## 1.18.14 (February 28, 2019)

### Fixed

- Fix IE11 compatibility

## 1.18.13 (February 28, 2019)

### Fixed

- Fix missing icon files

## 1.18.12 (February 27, 2019)

### Fixed

- Fix `custom js` setting with multiple `<script>` tags

## 1.18.11 (February 27, 2019)

### Added

- Add more margin options for fields to all elements
- Add color option for inlined SVGs to elements

### Changed

- Copy theme settings to child theme on first activation (WordPress)

### Fixed

- Fix button text style
- Fix image border option if aligned between in Panel and Grid elements
- Fix centering the map element with a hidden marker
- Fix rare occasion where Grid element content was not being displayed
- Fix video/image setting not being displayed in Section element
- Fix PHP < 5.6 compatibility (WordPress)
- Fix editing widgets, after previously saving them (WordPress)
- Fix preview reloading when empty anchors are clicked (WordPress)
- Fix saving anchor tags with `target` attribute in Builder (WordPress)
- Fix duplicate title in module positions (Joomla)
- Fix `My Elements` showing items in empty element library

## 1.18.10 (February 13, 2019)

### Changed

- Allow image border option for cards in Panel and Grid elements

### Fixed

- Fix class loading error 'YOOtheme\Theme\Widgets' not found (WordPress)

## 1.18.9 (February 13, 2019)

### Added

- Add stroke animation option for inline SVG to Grid, List, Switcher and Table elements

### Fixed

- Fix lazy image loading for Switcher and Popover element
- Fix box decoration preventing links from being clickable in some styles
- Fix Gutenberg editor enabled on WooCommerce product posts (WordPress)
- Fix checkbox checkmark in Finder

## 1.18.8 (February 8, 2019)

### Added

- Add stroke animation option for inline SVG

### Changed

- Better placeholder values in all elements

### Fixed

- Fix missing status option in some elements
- Fix set x and y properties simultaneously in parallax animation
- Fix image upload progress never finish (Joomla)

## 1.18.7 (February 6, 2019)

### Fixed

- Fix color picker

## 1.18.6 (February 5, 2019)

### Fixed

- Fix grid settings in J!Position element (Joomla)
- Fix clicking links in cookie banner also accepting cookies
- Fix display options in blog and post layout settings (WordPress)

## 1.18.5 (February 5, 2019)

### Fixed

- Fix endless update loop in preview

## 1.18.4 (February 4, 2019)

### Fixed

- Fix empty `layout` in JSON string in layouts
- Fix custom elements not being loaded (WordPress)
- Fix menu style setting in J!Module element (Joomla)
- Fix preview all UI components in style customizer

## 1.18.3 (February 1, 2019)

### Fixed

- Fix error when loading `js/custom.js`

## 1.18.2 (February 1, 2019)

### Fixed

- Fix PHP 7.3 compatibility in regex syntax
- Fix WooCommerce module loading (WordPress)

## 1.18.1 (January 31, 2019)

### Fixed

- Fix shortcodes not being applied to builder content (WordPress)
- Fix footer builder rendering in child theme override
- Fix PHP 5.4 syntax error in Newsletter element
- Fix `Clear Cache` button seemingly not clearing the cache

## 1.18.0 (January 31, 2019)

### Added

- Add status option to disable sections, rows and elements
- Allow for js/custom.js in child theme

### Changed

- Implement render tree concept for the page builder
- Improve saved page content
- Refactor element file structure

### Fixed

- Fix display of categories in breadcrumbs

## 1.17.7 (January 15, 2019)

### Fixed

- Fix offcanvas overlay transition
- Fix active state in Switcher element
- Fix background images on displays with higher devicePixelRatio in Img component
- Fix `YOOtheme Builder` button SEF URL plugin compatibility (Joomla)
- Fix `limit content length` blog setting

## 1.17.6 (January 14, 2019)

### Added

- Add `Horizontal Center Logo` layout to header layouts

### Fixed

- Fix navbar menu rendering in header position
- Fix warning if widget is assigned to a language in WP Widget element (WordPress)
- Fix modal video fullscreen option in image element

## 1.17.5 (January 04, 2019)

### Added

- Add Breadcrumbs Widget (WordPress)
- Add option to require mandatory consent before loading cookies

### Fixed

- Fix Gutenberg editor integration (WordPress)
- Fix alignment of justified dropdowns in navbar with dropbar

## 1.17.4 (December 21, 2018)

### Fixed

- Fix cookie notification appear below maps
- Fix invisible images in large galleries
- Fix `YOOtheme Builder` button multilanguage support (Joomla)
- Fix cancelling after prior save in customizer (Joomla)
- Fix PostgreSQL database compatibility (Joomla)
- Fix empty image container when no image selected in post (Wordpress)

## 1.17.3 (December 14, 2018)

### Fixed

- Fix customizer not loading in IE and Edge browser

## 1.17.2 (December 14, 2018)

### Fixed

- Fix API Key handling
- Fix missing toolbar layout (Wordpress)

## 1.17.1 (December 13, 2018)

### Added

- Add gradient option to media settings in Section and Row elements
- Add color option for meta field in all element
- Add box decoration inverse option to builder elements
- Add inverse style for box decorations in all styles

### Changed

- Optimize box decorations for all styles

### Fixed

- Fix create new table element
- Fix display search in header or navbar position (Wordpress)

## 1.17.0 (December 05, 2018)

### Added

- Add local style library
- Add footer layouts to library
- Add Cookie Banner panel to settings
- Add delete buttons to menu item and module modal (Joomla)
- Add option to extend the max width to left or right in sections and rows
- Add option to remove left or right section/row padding to all media elements
- Add box decoration option to builder elements
- Add inline SVG option to Accordion, Popover, Slideshow, Switcher and Table element
- Add option to shrink thumbnav into a single line in Slideshow element
- Add option to insert layout from the library at the top of the current layout

### Changed

- Memorize the option how a layout is inserted through the library

### Fixed

- Fix Unsplash library needing refresh on apikey change
- Fix Height Viewport calculation (IE11)
- Fix Link Toolbar not hiding in Visual Editor (WordPress)
- Fix using data URIs in custom element CSS
- Fix using CSS escape characters in selectors in custom element CSS
- Fix visibility setting for toolbar + navbar widgets/modules
- Fix render widget container when not assigned to current language (WordPress)
- Fix article editor 'save' no longer corrupts builder layouts
- Fix slideshow 'pause on hover' setting
- Fix `YOOtheme Builder` button does not open into `builder` section (WordPress)
- Fix issue with Page Builder getting out of sync with preview
- Fix superfluous page change notifications under certain conditions

## 1.16.7 (November 24, 2018)

### Fixed

- Fix redirect after closing customizer (Joomla)
- Fix IE11 compatibility
- Fix Advanced Module Manager compatibility (Joomla)
- Fix commentform translation (WordPress)

## 1.16.6 (November 23, 2018)

### Fixed

- Fix markup in element templates

## 1.16.5 (November 23, 2018)

### Changed

- Refactor all element templates

### Fixed

- Fix module header tag setting not reflected in J!Module element (Joomla)
- Fix superfluous page change notifications when post has no revision (WordPress)
- Fix IE11 compatibility
- Fix transparent navbar header

## 1.16.4 (November 20, 2018)

### Fixed

- Fix size of background images on retina displays
- Fix image urls in Map element markers
- Fix autoplay option in Video element
- Fix superfluous page change notifications under certain conditions
- Fix menu item class on separator items
- Fix 'Enable click mode on text separators' mode (WordPress)

## 1.16.3 (November 16, 2018)

### Changed

- Improved System Check

### Fixed

- Fix duplicating actions on clicking buttons in page builder
- Fix regression with modals not closing
- Fix Offcanvas changing viewport upon opening
- Fix notice on WooCommerce pages (WordPress)

## 1.16.2 (November 13, 2018)

### Fixed

- Fix "New layout" button in footer, module and widget builders
- Fix duplicating images in item list for builder elements with content items
- Fix superfluous page change notifications under certain conditions
- Fix sticky navbar not recalculating
- Fix media picker in link picker field
- Fix title in builder widget (WordPress)
- Fix page builder closes unexpected on touch devices
- Fix change footer-builder title

## 1.16.1 (November 09, 2018)

### Added

- Add warning for uninstalled/disabled `installer` plugin in API key section

### Fixed

- Fix "YOOtheme Builder" button in article edit view (Joomla)
- Fix regression with modals not closing
- Fix audio element disappear in Chrome

## 1.16.0 (November 08, 2018)

### Added

- Add buttons to hide/show customizer (Joomla)
- Add button to drag the width of the customizer (Joomla)
- Add notification before saving if layout has been updated by another user
- Add confirmation before replacing layout through library
- Add sticky navbar options to mobile header
- Add multiple items from media manager to builder elements
- Add image to item list for builder elements with content items
- Add default icon and icon color option to List element
- Add vertical alignment option to List element
- Add name, id, class and CSS fields to HTML element
- Add max width options to Grid and Gallery element
- Add lazy loading to Maps element
- Add text color emphasis option for title and icon fields
- Add text shadow picker in style customizer
- Add font-style option to blockquote footer
- Add button to rename files to media manager (Joomla)
- Add module id to select in J!Module element (Joomla)
- Add module id to edit module modal (Joomla)
- Add System Check panel to settings
- Add API Key panel to settings (Joomla)
- Add print, reddit, microphone, file-text, file-pdf and more to icon field
- Add support for the Gutenberg editor (WordPress)

### Changed

- Adjust sidebar for all builder panels to the same width
- Make layout save button sticky and available in all builder panels
- Move 3rd party options to External Services panel in settings
- Move CMS specific options from System panel to Advanced panel in settings
- Move button to start a new layout to initial builder panel
- Update top and bottom settings according to latest builder section settings

### Fixed

- Fix text filter issue for any filter group and type (Joomla)
- Fix background image srcset resizing for top and bottom position
- Fix extra white space below masonry grid
- Fix 'inset' option in boxshadow picker
- Fix vertical alignment in search style "Modal" for IE11

## 1.15.7 (October 16, 2018)

### Fixed

- Fix PHP notice if columns not defined in blog (WordPress)
- Fix menu images in `Stacked Center Split` navbar layout

## 1.15.6 (October 15, 2018)

### Added

- Add width option and responsiveness to Map element
- Add height setting to rows
- Add bottom option for vertical alignment of column

### Changed

- Match height option now matches not just single panels in row settings
- Moved vertical alignment option from row to column settings
- Improve background image sizes

### Fixed

- Fix match height of panels if overlay color is used for column image
- Fix vertical alignment if tile or image is used for column
- Fix padding option not being available if column image is used
- Fix z-index issue if using menu animation with dropdown slide in mobile header
- Fix storing layouts containing elements without content items
- Fix the sticky navbar not being sticky on small viewports in rare cases
- Fix menu link picker filter dropdowns in Firefox (Joomla)

## 1.15.5 (October 08, 2018)

### Fixed

- Fix setting empty default values in builder causes refresh and dirty state
- Fix "Theme" button on edit widgets (WordPress)
- Fix images in customizer mode still being saved (WordPress)
- Fix readmore link redirect after login (Joomla)
- Fix could not save module settings after error occured (Joomla)
- Fix replacing images in builder layouts
- Fix initially wrong position of background image with parallax

## 1.15.4 (October 02, 2018)

### Added

- Add divider vertical style

### Changed

- Compile WooCommerce style only when plugin is active (WordPress)

### Fixed

- Fix encoded thumbnail URI in media manager list (Joomla)
- Fix builder toolbars in footer
- Prevent refresh after clicking builder toolbar (WordPress)
- Fix resetting of gradient color field in style customizer

## 1.15.3 (September 28, 2018)

### Fixed

- Fix style inspector missing in "Preview all UI elements"
- Fix builder element highlighting in preview
- Fix opening changelog multiple times
- Fix social icon element loosing values upon edit
- Fix missing preview images in PHP < 7

## 1.15.2 (September 27, 2018)

### Fixed

- Fix builder closing its panels unexpected
- Fix link picker only selecting images (Joomla)

## 1.15.1 (September 26, 2018)

### Fixed

- Fix empty style customizer
- Fix font handling in style customizer
- Fix builder layout not refreshing when browsing to another page in preview
- Fix not being able to create new builder layout (WordPress)
- Fix builder if opened through module edit view (Joomla)
- Fix article and menu buttons in visual editor (Joomla)
- Fix newsletter API settings
- Fix editing Builder Widgets (WordPress)
- Fix changelog in Safari (WordPress)
- Fix Lilian Less typo

## 1.15.0 (September 25, 2018)

### Changed

- Upgrade to Vue 2.5
- Refactor JavaScript application
- Add new color picker
- Improve image creation in page builder

## 1.14.8 (September 14, 2018)

### Fixed

- Fix regression in Newsletter element
- Fix regression with resizing images loaded from Layout Library
- Fix match height of panels if column image with overlay color is used
- Fix show image option not working with icon field in Grid and List element

## 1.14.7 (September 06, 2018)

### Added

- Add form style option to newsletter element

### Fixed

- Fix LESS syntax error in white-blue Flow style

## 1.14.6 (September 05, 2018)

### Added

- Add Flow style
- Add pill to border option in elements
- Allow `.m4v` file selection in media picker

### Changed

- Allow logo to expand navbar height
- Reduce animation delay to 200ms

### Fixed

- Fix Animation setting for Grid/Gallery elements
- Fix Lightbox caption in Grid element
- Fix Blog/Post image missing alt attribute
- Fix Header `Stacked Center Split` with menu modules and caching (Joomla)
- Fix images load wrong srcset in Edge browser

## 1.14.5 (August 21, 2018)

### Added

- Add option to inline SVGs in Image, Panel, Grid and List element

### Changed

- Update administration language files

### Fixed

- Fix filter if tags are numerical values
- Fix LESS highlighting in custom CSS/LESS field

## 1.14.4 (August 09, 2018)

### Fixed

- Fix video file selection in media picker
- Fix JCE editor (Joomla)
- Fix smart search advanced options not visible (Joomla)

## 1.14.3 (August 08, 2018)

### Added

- Add padding option to blog and post settings
- Add width option `None` to post settings
- Apply blog settings in tag view (Joomla)

### Fixed

- Fix TinyMCE losing focus in Edge
- Fix overriding `$post` (WordPress)

## 1.14.2 (August 03, 2018)

### Fixed

- Fix blog center title and meta setting (WordPress)
- Fix IE 11 regression in customizer
- Fix PHP < 5.6 compatibility with Font saving
- Fix handling error on Font saving

## 1.14.1 (August 02, 2018)

### Fixed

- Fix notice on blog archive (WordPress)

## 1.14.0 (August 02, 2018)

### Added

- Add margin options to blog and post settings
- Add width options to blog and post settings
- Add title style option to blog settings
- Add breakpoint option to blog settings
- Add masonry option to blog settings
- Add parallax option to blog settings
- Add option to limit the content length
- Add option to hide readmore button and comments count (WordPress)
- Add warning if Text Filters are active (Joomla)
- Add support for smart search (Joomla)

### Changed

- Show drop cap in post view only
- Center blog image if smaller than the container

### Removed

- Remove blog column order (WordPress)
- Remove divider option in blog settings
- Remove grid if just one blog column

### Fixed

- Fix layout blog settings not applied to featured blog (Joomla)
- Remove empty `div` if there is no blog intro text
- Fix link style being disabled in Icon element
- Fix jumping in filtered Grid during animation
- Fix phone number or email adress opening a new tab in social icons
- Handle error on save (Joomla)

## 1.13.2 (July 13, 2018)

### Added

- Add option to shrink thumbnav into a single line in Switcher element
- Add color option button text arrow icon in style customizer
- Add border option for table divider header in style customizer

### Fixed

- Fix modal not covering viewport in Button element

## 1.13.1 (July 10, 2018)

### Fixed

- Fix margin if thumbnav wraps into multiple lines in Switcher element
- Fix Gallery element with `Min Height` applied
- Fix Layout library modal jumping in size indefinitely
- Close current Builder Section upon saving external images only
- Optimize unsplash preview image sizes
- Fix double encoded iframe src

## 1.13.0 (July 05, 2018)

### Added

- Add Unsplash image library
- Store and load Google Fonts locally

### Fixed

- Fix route generating (Joomla)
- Exclude GIF images from resizing

## 1.12.6 (June 20, 2018)

### Fixed

- Fix Dropbar in IE 11
- Fix PNG image transparencies

## 1.12.5 (June 18, 2018)

### Fixed

- Fix images wrong positioned in Gallery and Overlay element
- Raise PHP memory_limit on image operations
- Fix module rendering in `navbar` position in PHP 5 (Joomla)

## 1.12.4 (June 11, 2018)

### Fixed

- Apply translations to element names
- Fix clicking on filter items in Customizer (WordPress)
- Fix Masonry Grid not having correct height
- Fix module rendering in `navbar` position (Joomla)
- Fix iframe jumping introduced with Chrome 66

## 1.12.3 (June 06, 2018)

### Fixed

- Fix PHP < 5.5 compatibility with Image creation
- Fix cause for missing images
- Fix error for none existing image in Gallery and Overlay element
- Fix filter if tags are numerical values

## 1.12.2 (June 05, 2018)

### Fixed

- Fix webp config setting
- Fix internal link handling (Joomla)
- Fix missing menu module params in `navbar` position (Joomla)
- Apply Link Class to anchors instead of list items in Navs (Joomla)

## 1.12.1 (June 05, 2018)

### Fixed

- Fix PHP < 7 compatibility

## 1.12.0 (June 04, 2018)

### Added

- Add filter options to Grid and Gallery element
- Add `masonry` option to Grid and Gallery element
- Add general lazy loading option for images
- Add lazy loading option to Video element
- Add option which play/pause the Video element as it enters/leaves the viewport
- Add smaller font-sizes on mobile for h1, h2, primary and hero heading and article title

### Changed

- Use image elements instead of background images in Overlay and Gallery elements

### Fixed

- Resize poster image to video dimensions in Video element
- Disable video options for `youtube-nocookie.com` domain in Video element
- Fix gallery shortcode rendering (WordPress)
- Header Layout `Stacked Center Split` usable with menu modules

## 1.11.9 (May 04, 2018)

### Added

- Add Mailchimp double opt-in setting to Newsletter element

### Fixed

- Fix Videos not auto play/pause in modals
- Fix Gallery images missing alt attribute in Lightbox
- Fix multiple Popover elements overlapping each other
- Fix sh404sef error handling compatibility

## 1.11.8 (April 11, 2018)

### Fixed

- Fix Offcanvas/Modal not opening more than once
- Fix regression with fullscreen Slideshow element

## 1.11.7 (April 05, 2018)

### Added

- Add large size option to Table element
- Add full width option for buttons to Table element
- Add heading hero option to panel and Grid element

### Fixed

- Fix boxed layout background image uses relative URL (WordPress)
- Fix missing menu icon class (Joomla)
- Fix Tab key navigation in Customizer
- Fix Customizer translation based on user profile setting (WordPress)
- Fix Google Font Montserrat font-weight (WordPress)

## 1.11.6 (March 15, 2018)

### Fixed

- Fix loop youtube videos
- Fix player ids in Vimeo videos
- Fix prefix custom CSS for elements
- Fix grouped input field loosing focus upon first input
- Child theme can override offline.php (Joomla)

### Removed

- Remove redundant WooCommerce setting for WooCommerce 3.3 (WordPress)

## 1.11.5 (March 06, 2018)

### Fixed

- Fix Pinewood-Lake navbar letter-spacing

## 1.11.4 (March 05, 2018)

### Added

- Add options to remove margin to row settings in builder
- Add border options to navbar in style customizer

### Fixed

- Fix SH404SEF plugin compatibility (Joomla)
- Fix EasySocial plugin compatibility (Joomla)
- Fix overlapping sections if section has a background image
- Fix missing menu item title attribute (WordPress)
- Fix padding option if an image is selected for builder columns
- Fix dropbar opens on bottom positioned drops only
- Fix section overlap textures in fjord style for Firefox and Safari

## 1.11.3 (February 12, 2018)

### Fixed

- Fix YouTube short urls in Lightbox
- Fix Google Font Montserrat font-weight

## 1.11.2 (February 02, 2018)

### Changed

- Rename Vision style dark-green to black-green

## 1.11.1 (February 01, 2018)

### Added

- Add Vision style

## 1.11.0 (January 31, 2018)

### Added

- Add Slider element to builder
- Add slidenav position outside to Slideshow element
- Add color option for navigations to Slideshow element
- Add option for link hover effect to Heading element
- Add support for privacy enhanced mode in YouTube urls

### Changed

- Update title style in Accordion element
- Improve image srcset resizing
- Update list of available Google Fonts
- YouTube/Vimeo options are now settable through their URL in Video element

### Fixed

- Make Accordion element accessible through keyboard
- Make overlays in Overlay and Gallery element accessible through keyboard
- Add debug backtrace to error.php (Joomla)
- Child theme can override error.php (Joomla)
- Allow uploading PDF files in media manager (Joomla)
- Fix Editors loosing focus upon typing first letter

## 1.10.8 (December 18, 2017)

### Fixed

- Fix select image from media-library (WordPress)

## 1.10.7 (December 14, 2017)

### Added

- Highlight selected image in media-library (WordPress)
- Add page builder link to pages list (WordPress)

### Fixed

- Fix preview refreshing when changing style (WordPress)
- Fix link picker url path (WordPress)
- Fix module rendering in format raw mode (Joomla)
- Fix new routing method compatibility in error.php (Joomla)

## 1.10.6 (December 05, 2017)

### Fixed

- Fix pagination on Windows platforms (Joomla)
- Add missing error.php to templateDetails.xml (Joomla)

## 1.10.5 (December 01, 2017)

### Added

- Add reset filter button to style and layout library
- Add border mode to alert in style customizer
- Add font options to search in style customizer
- Add error page (Joomla)

### Fixed

- Create separate `theme.css` for multisites (WordPress)
- Create separate `theme.css` for template styles (Joomla)
- Include missing preventAutofocus.js file in dist
- Fixed validation messages not shown in frontend editing (Joomla)

## 1.10.4 (November 16, 2017)

### Added

- Make html/pagination.php overwritable in child theme (Joomla)

### Fixed

- Fix error in JSON views (Joomla)
- Prevent `input` elements with `autofocus` attribute from gaining focus in customizer preview

## 1.10.3 (November 13, 2017)

### Fixed

- Fix media manager in frontend edit (Joomla)
- Fix IE 11 regression

## 1.10.2 (November 09, 2017)

### Changed

- Load jQuery automatically if used in `theme.js` in child themes (WordPress)

### Fixed

- Fix Accordion elements not opening
- Fix failed loading Icomoon fonts (Joomla)
- Fix Slideshow YouTube videos play inline on mobile

## 1.10.1 (November 08, 2017)

### Changed

- Load jQuery automatically if used in `theme.js` in child themes (Joomla)

### Fixed

- Fix CSS auto-compiling after theme update
- Fix multi-line selectors in custom CSS field in the builder
- Fix rare fatal error in child themes with 3rd party modules
- Prevent saving the style customizer if a Less error occurs

## 1.10.0 (November 07, 2017)

### Added

- Add Slideshow element to builder
- Add controls, muted and playsinline options to Video element
- Add advanced coloring and parallax options to builder column
- Add max width breakpoint option to all builder elements
- Add reset style button to style customizer components overview
- Add UI preview for the theme component to style customizer
- Add thumbnav component to style customizer
- Add large margin option to Overlay and Gallery element

### Changed

- Rework UI preview in style customizer
- Rework save and cancle handling in style customizer
- jQuery is no longer be loaded by default, enable corresponding setting to load it
- Allow to select a style and a video for sections at the same time
- Switcher element is now using the thumbnav style for thumbnail navigation
- Update dotnav and slidenav styles in all styles

### Removed

- Remove fallback image and use playsinline attribute instead for section videos

### Fixed

- Fix background image breaking style textures for builder column
- Fix visual editor default settings (WordPress)
- Fix render widgets in custom positions (WordPress)
- Fix breaking `Stacked Center A` header layout
- Fix invalid connect_ids for Switcher, Image and Button element
- Fix animated navbar line in Safari
- Fix markup in Joomla Module Element
- Fix Gallery and Grid Elements support YouTube and Vimeo URLs
- Fix Lightbox with anchor elements in description

## 1.9.9 (September 29, 2017)

### Added

- Add option to center logo for modal and offcanvas header layouts
- Add z index option to parallax settings
- Add UI preview for the inverse component to style customizer

### Changed

- Optimize CSS and markup for boxed page option
- Move border mode for body element to theme page class
- Rework UI preview pages of all components for style customizer

### Fixed

- Fix overlay max width option in Overlay and Gallery element
- Fix search icon style in navbar for modal mode
- Fix TinyMCE notification z-index
- Fix email cloaking cleanup (Joomla)
- Fix comments pagination links (WordPress)
- Fix form blank focus style

## 1.9.8 (September 25, 2017)

### Added

- Enable page builder in substyles (Joomla)

### Fixed

- Fix MailChimp provider shows only up to 10 lists in Newsletter element
- Fix Module element closing title tag (Joomla)

## 1.9.7 (September 19, 2017)

### Fixed

- Fix RTL theme CSS include (WordPress)
- Fix link picker with multiple menus (Joomla)
- Fix module position renderer (Joomla)

## 1.9.6 (September 8, 2017)

### Fixed

- Fix image srcset sizing

## 1.9.5 (September 7, 2017)

### Fixed

- Fix image preview in layouts

### Changed

- Optimize less source file loading

## 1.9.4 (August 30, 2017)

### Fixed

- Fix submenus in nav rendering
- Fix grid and gallery animation settings
- Fix animated navbar line in Firefox
- Fix show UI components option for boxed page layouts with vertical padding
- Fix prevent duplicate meta charset (Joomla)
- Fix link picker (WordPress)

## 1.9.3 (August 18, 2017)

### Fixed

- Fix navbar animations in Firefox
- Fix Module Class Suffix in module overrides (Joomla)
- Fix boxed container width and background

## 1.9.2 (August 16, 2017)

### Fixed

- Fix centered toolbar-right position if toolbar-left is empty
- Fix unsorted inverse variables in style customizer
- Fix system overrides in child-theme (Joomla)
- Fix layout select field

## 1.9.1 (August 03, 2017)

### Fixed

- Fix editing builder module
- Fix 'Enable click mode on text separators' mode

## 1.9.0 (August 01, 2017)

### Added

- Add lightbox gallery to Grid and Gallery element
- Add lightbox options to image and button element
- Add section title options
- Add vertical alignment option if section height is larger than its content
- Add mixed image orientation mode to Gallery and Grid element
- Add min-height option to Overlay and Gallery element
- Add support for hover image only to Overlay and Gallery element
- Add option to center the toolbar
- Add option to pick files to all link fields
- Child theme can overwrite more template files, e.g. index.php and offline.php
- Child theme loads custom elements from builder/ directory
- Child theme loads custom module from config.php

### Changed

- Check for script tags in custom script code
- Refactored tabs and settings UI for sections
- Add link picker to redirect field in Newsletter element
- Improve dropbar style in some themes

### Fixed

- Fix HTML element option in Divider element
- Fix Id option in Switcher element
- Fix sticky navbar behaviour
- Fix dropdown position if navbar has responsive height

## 1.8.5 (July 26, 2017)

### Fixed

- Fix content style setting in accordion
- Fix opening anchor links from the Offcanvas menu
- Fix rare case of "Invalid CSRF token" upon saving (WordPress)

## 1.8.4 (July 19, 2017)

### Fixed

- Fix email cloaking cleanup (Joomla)
- Fix title attributes on menu anchors (Joomla)

## 1.8.3 (July 12, 2017)

### Fixed

- Fix email cloaking issue (Joomla)

### Changed

- Update administration language files

## 1.8.2 (July 10, 2017)

### Fixed

- Fix library in Builder Module (Joomla)
- Fix compatibility with yoast plugin (WordPress)

## 1.8.1 (July 05, 2017)

### Fixed

- Fix Google Fonts issue
- Disable general field instead of hide
- Fix highlight search result (Joomla)

## 1.8.0 (July 04, 2017)

### Added

- Add style library
- Add 45 color variations to style library
- Add link picker
- Add group field type
- Add divider attribute for fields

### Changed

- Refactored tabs and settings UI for elements
- Layout library remembers filters when closed
- Use range picker instead of text fields in Map element
- Update administration language files
- Set gestureHandling to cooperative by default for google maps
- Activated double opt-in for MailChimp in Newsletter element

### Fixed

- Fix link/title bullet combination for Panel and Grid element
- Fix template settings in module front-end editing view
- Fix nav hover style in menu panel (Joomla)
- Offcanvas in overlay mode will close and keep scroll position if anchor link was clicked

## 1.7.7 (June 26, 2017)

### Fixed

- Fix error with saving Pro Layouts

## 1.7.6 (June 23, 2017)

### Fixed

- Fix notice on article edit screen (Joomla)
- Fix error in style customizer (WordPress)


## 1.7.5 (June 21, 2017)

### Fixed

- Fix article TOC display (Joomla)
- Fix article multiple categories styling (WordPress)
- Allow for script tags in builder data (WordPress)
- Fix panel widths in customizer (WordPress)

## 1.7.4 (June 14, 2017)

### Fixed

- Fix issue with custom fields saving (Joomla)
- Fix transparent navbar with builder module on top position

## 1.7.3 (June 08, 2017)

### Changed

- Use range picker instead of text fields for Popover element
- Media Picker remembers last used view (thumbnail / list) upon opening (Joomla)

### Fixed

- Fix MailChimp name fields in Newsletter element
- Fix frontend editing
- Fix builder module/widget warning messages
- Fix Joomla Position/WordPress Area Elements names searchable

## 1.7.2 (June 07, 2017)

### Added

- Add J! Position builder element
- Add WP Area builder element
- Add dedicated builder module positions and widget areas

### Changed

- Sort less export by variable names

## 1.7.1 (June 02, 2017)

### Added

- Add 500px icon to social icons

### Changed

- Put custom JavaScript code in isolated script tag

### Fixed

- Fix issue with 3rd party extensions (Joomla)

## 1.7.0 (June 01, 2017)

### Added

- Add Juno style
- Add builder module/widget
- Add newsletter element to builder
- Add popover element to builder
- Add parallax options to builder elements
- Add image parallax option to builder section
- Add parallax mode to grid and gallery element
- Add width and height option for logos
- Add marker component to style customizer
- Add form range component to style customizer
- Add xlarge padding option to builder column
- Add support menu header item (WordPress)
- Add div to HTML element option for the headline element
- Add link text option to builder elements and style customizer
- Add border mode for body to base
- Add link field to headline element
- Add max width option to more builder elements

### Changed

- 'uk-*' attributes are preserved in visual editor
- Collapse layout if Module/Widget elements have no output
- Media Picker remembers last browsed folder upon opening (Joomla)
- Empty builder by deleting its last section

### Fixed

- Fix width and height option for SVG image on sections and tiles
- Fix frontend editing and module class suffix for Joomla modules
- Fix column option for large screens in Grid and Gallery element
- Fix display options if navigation is bottom aligned for switcher element
- Fix prevent frontend editing when no user is logged in (Joomla)
- Fix library media download (Joomla)
- Allow '0' as content in builder elements
- Do not render filtered widgets in WP Widget element (WordPress)
- Link to page builder from article edit view contains correct Itemid now (Joomla)

## 1.6.5 (May 09, 2017)

### Fixed

- Fix bottom aligned thumbnails for switcher element
- Fix navbar behaviour for none transparent sections
- Fix emptying layouts in builder behaviour
- Fix navbar behaviour (iOS < 9.3)

## 1.6.4 (May 04, 2017)

### Fixed

- Fix builder toolbar being shown without open builder
- Fix editor for users with limited access (WordPress)
- Fix less error messages on custom less field
- Fix drag and drop in builder on devices with both mouse and touch
- Fix syntax highlighting in custom JavaScript field

## 1.6.3 (May 02, 2017)

### Added

- Add Vibe style

### Fixed

- Prevent nested &lt;p&gt; tags in builder page

## 1.6.2 (April 28, 2017)

### Added

- Add fallback image for touch devices for video sections

### Changed

- Disable visual editor when no visual system editor enabled

### Fixed

- Fix UIkit/Master theme styles with custom fonts
- Countdown element supports more than two digits
- Fix header mobile layout offcanvas overlay mode
- Fix 'Link' label in style customizer

## 1.6.1 (April 27, 2017)

### Changed

- Visual editor use line breaks instead of paragraphs for heading element

### Fix

- Fix incompatibility with PHP versions < 5.4.32, 5.5.16

## 1.6.0 (April 26, 2017)

### Added

- Add system WYSIWYG editor
- Add table element to builder
- Add countdown element to builder
- Add option to anonymize IPs for Google Analytics
- Add box-shadow bottom option to builder elements
- Add shadow options to video element
- Add font-style option to style components
- Add border gradient support to base, button and tab
- Add gradient support to navbar container

### Changed

- Update UIkit to 3.0.0 beta22
- Update table component according to UIkit
- Start playing animated Gifs when entering the viewport

### Fix

- Fix list striped component
- Fix select box color if bg is transparent in IE11 and Edge

## 1.5.7 (April 21, 2017)

### Changed

- Renamed module template parameter (Joomla)

### Fixed

- Fix arguments in widget element (WordPress)
- Fix menu rendering in widget element (WordPress)
- Fix Navbar in RTL mode

## 1.5.6 (April 19, 2017)

### Fixed

- Fix customizer session handling (Joomla)

## 1.5.5 (April 19, 2017)

### Fixed

- Fix article view ACL (Joomla)

## 1.5.4 (April 18, 2017)

### Fixed

- Fix icons
- Fix article view ACL (Joomla)

## 1.5.3 (April 18, 2017)

### Changed

- Updated WooCommerce style (WordPress)

### Fixed

- Fix Navbar Dropbar

## 1.5.2 (April 13, 2017)

### Changed

- Improve Builder sorting performance
- Accordion menu parent items (WordPress)

### Fixed

- Fix openstreetmap not using https
- Fix Builder memory leaks
- Fix WooCommerce 3.x product gallery

## 1.5.1 (April 06, 2017)

### Fixed

- Fix text alignment for Button, Social and Subnav elements
- Fix mobile dropdown menu
- Fix duplicate module/widget ids
- Fix expand mode on 'main' container

## 1.5.0 (April 04, 2017)

### Added

- Add Copper Hill style
- Add custom CSS field for builder elements
- Add style and alignment options for offcanvas and modal header layouts
- Add style and alignment options for mobile layouts
- Add more fields and options to description-list element
- Add google maps url support in social links
- Add tripadvisor and yelp to icons
- Add autocomplete to code fields
- Add gradient support to card hover
- Add options to remove left logo padding in header
- Add typo options to logo, table and description-list in style customizer
- Add padding options vertical tabs in style customizer
- Add transitions to nav items

### Changed

- Improve Google font loading
- Improve offcanvas and modal header layouts
- Navs with parent links are no longer rendered as Accordion
- Wrap page in offcanvas content div
- Make navbar toggle text look like navbar nav items
- Updated administration language files

### Fixed

- Fix offcanvas scrolling on touch devices

## 1.4.3 (March 17, 2017)

### Fixed

- Fix missing RTL stylesheet

## 1.4.2 (March 17, 2017)

### Fixed

- Fix background video height in sections
- Fix Javascript error when using multiple Map elements in builder
- Fix wrong locale no-NO in language files

## 1.4.1 (March 16, 2017)

### Fixed

- Fix missing builder elements
- Fix "Pro Layouts" tab in layout library

## 1.4.0 (March 16, 2017)

### Added

- Add overlay element to builder
- Add gallery element to builder
- Add switcher element to builder
- Add more fields and options to accordion element
- Add row column settings in builder
- Add tile component to style customizer
- Add 25 site and 11 admin localizations
- Add support for RTL languages

### Changed

- Update outdated CSS when opening customizer

### Fixed

- Fix menu style setting in widget element (WordPress)
- Fix "Preview all UI elements" disable on style change
- Fix rendering of additional menus in navbar with layout `Stacked Center Split`
- Fix loop youtube videos
- Fix PageBuilder button in IE 11

## 1.3.13 (March 07, 2017)

### Fixed

- Fix transparent header behaviour
- Fix builder in Firefox

## 1.3.12 (March 03, 2017)

### Fixed

- Fix "sticky on up" behaviour in Navbar

## 1.3.11 (March 02, 2017)

### Changed

- Disable 'Visual' Editor button on page edit view (WordPress)

### Fixed

- Fix JavaScript error in frontend
- Fix navbar groups center left/right in IE 11
- Fix alt attribute for images in grid element
- Fix blockquote inverse color in fjord style
- Fix panel animations in customizer (WordPress)
- Fix transparent png issue if resized

## 1.3.10 (March 02, 2017)

### Fixed

- Fix transparent navbar overlay
- Fix widgetkit compatibility (WordPress)

## 1.3.9 (March 01, 2017)

### Added

- Add Sonic style
- Add background image option for all section styles
- Add gutter and breakpoint option to description-list element
- Add text color setting to headline, module / widget and panel element
- Add on hover box-shadow option to image element
- Add support for heading hero
- Add display link option to list element
- Add image box-shadow option to panel and grid element
- Add receiver icon to social icons
- Add gradient support to divider, icon, navbar, progress and subnav

### Changed

- Use JS solution for transparent headers

### Fixed

- Fix image background position center for sections
- Fix responsive breakpoints for Quarters 1-2-1 row layout
- Fix display image option in list element
- Fix modules/menu items staying checked out after edit (Joomla)
- Fix mod_menu apply additional params (Joomla)
- Fix image transparency issue on resize

## 1.3.8 (February 17, 2017)

### Fixed

- Fix Grid element
- Fix library layout saving
- Fix Maps element
- Fix custom menu widget rendering (WordPress)
- Fix saving widget settings (WordPress)

## 1.3.7 (February 16, 2017)

### Fixed

- Fix images in pro layouts overview
- Fix justified dropdown menu item
- Fix Media Picker (Joomla)

## 1.3.6 (February 15, 2017)

### Fixed

- Fix module select in builder (Joomla)
- Fix finder module advanced mode (Joomla)

## 1.3.5 (February 14, 2017)

### Fixed

- Fix builder issue with empty value

## 1.3.4 (February 13, 2017)

### Added

- Show builder toolbars in footer
- Add menu style setting for menu modules/widgets
- Add image align bottom option to panel and grid element

### Changed

- Update UIkit to 3.0.0 beta10

### Fixed

- Fix Google Fonts in font picker
- Fix social icons wrapping if navbar is centered
- Fix content change check on articles with tinyMCE 3.x (Joomla)

## 1.3.3 (February 06, 2017)

### Added

- Gradient field for style

### Fixed

- Fix scrollspy animations
- Fix content change check on articles with tinyMCE (Joomla)

## 1.3.2 (February 03, 2017)

### Changed

- Update UIkit to 3.0.0 beta9
- Update German admin language file

### Removed

- Custom dropdown width for single navbar items

### Fixed

- Fix Google font subset loading in IE
- Fix start/end level menu module setting (Joomla)
- Fix comment submit button style for iOS (WordPress)
- Fix Media Picker in frontend (Joomla)

## 1.3.1 (February 02, 2017)

### Changed

- Update UIkit to 3.0.0 beta8
- Revert header split menu behavior
- Check replace layout option by default

### Fixed

- Fix IE compatibility
- Section xsmall variable in style customizer
- Fix dotnav inverse variables
- Fix hidden title field for panel and grid element

## 1.3.0 (February 01, 2017)

### Added

- Add Fjord style
- Add layout library
- Add UIkit theme to style customizer
- Add German admin language file
- Add panel fields and options to grid element
- Add meta field and options to panel element
- Add image card and content style options to panel element
- Add option for overlapping sections with richer graphics
- Add more section height options
- Add gradient support to button, card, overlay and section
- Add background-image support to section

### Changed

- Move content settings into their own tab for panel element
- Move animations to grid items for grid element
- Update UIkit to 3.0.0 beta7

### Fixed

- Fix logo centering for split navbar header layout
- Fix image alignment in panel element
- Fix order option for breakpoints in builder rows
- Fix uploads in Media Picker (Joomla)
- Fix error messages in Media Picker (Joomla)
- Add missing video dimensions for video sections

## 1.2.16 (January 24, 2017)

### Added

- Add German language file (site)

### Changed

- Update UIkit to 3.0.0 beta6

### Fixed

- Fix module/widget list style
- Fix maps element (WordPress)

## 1.2.15 (January 23, 2017)

### Fixed

- Fix customizer settings (WordPress)

## 1.2.14 (January 22, 2017)

### Changed

- Update UIkit to 3.0.0 beta5

### Fixed

- Fix viewport height issue
- Fix IE + Edge compatibility

## 1.2.13 (January 21, 2017)

### Fixed

- Fix Jetpack compatibility (WordPress)

## 1.2.12 (January 19, 2017)

### Added

- Add inherit to typo options in style customizer
- Add font-style option to meta text in style customizer

### Fixed

- Fix IE + Edge compatibility

## 1.2.11 (January 18, 2017)

### Fixed

- Fix builder sorting
- Fix UIkit icon image path
- Fix preview module positions (Joomla)

## 1.2.10 (January 17, 2017)

### Added

- Add pagination option to toggle Start/End links (Joomla)

### Changed

- Update leaflet to 1.0.2
- Update dropdown nav in max style
- Update default code, pre and blockquote style
- Change global border-radius behaviour in all themes
- Change default value for zooming/dragging option in maps element
- Adapt customizer style to latest WordPress version (WordPress)

### Fixed

- Fix card component hover transition
- Fix image paths for divider and list icons in fuse and horizon style
- Fix WooCommerce gallery thumbnailview for WooCommerce 2.7+ (WordPress)

## 1.2.9 (January 02, 2017)

### Added

- Add Joline style
- Add box-shadow option to image element
- Add x-small padding to section options

### Changed

- Optimized CSS selectors for inverse mode
- Renamed UIkit internal image variables
- Update pagination output

### Fixed

- Fix form border for inverse mode
- Fix link style for inverse mode
- Fix blockquote footer color for inverse mode
- Fix badge focus style
- Fix categories count in article view (WordPress)

## 1.2.8 (December 23, 2016)

### Changed

- Renamed styles to website name

### Fixed

- Fix style parsing
- Fix navbar decorative line in Safari
- Fix module/widget list style on sections

## 1.2.7 (December 22, 2016)

### Fixed

- Fix asset cache breaker (Joomla)
- Fix inline style/script order (WordPress)

## 1.2.6 (December 21, 2016)

### Changed

- Update Module/Widget builder element select

### Fixed

- Fix double initialization (WordPress)

## 1.2.5 (December 20, 2016)

### Added

- Add list options for modules/widgets

### Fixed

- Fix builder sorting
- Fix navbar behaviour
- Fix resizing of images with non-alphanumeric characters
- Fix map element with hidden markers
- Fix render menu widgets (WordPress)
- Fix labels in navbar item edit (WordPress)
- Fix child-theme module overrides (Joomla)

## 1.2.4 (December 14, 2016)

### Changed

- Update "Read more" translation
- Customizer route redirects to login for guests (Joomla)

### Fixed

- Fix z-index issue for Map elements
- Fix Module/Widget builder element settings
- Fix code syntax highlighting
- Fix builder refresh issue (WordPress)
- Fix compatibility with Advanced Module Manager extension (Joomla)

## 1.2.3 (December 12, 2016)

### Fixed

- Fix edit template style (Joomla)

## 1.2.2 (December 12, 2016)

### Added

- Add button text style mode
- Add border option to dropdowns and subnav pill
- Add typo options to blockquote footer
- Add more text alignment options for modules/widgets
- Add border bottom option to headerbar top
- Add option to hide category title on archive view (WordPress)

### Changed

- Minor UIkit theme modifications

### Fixed

- Fix category multi-column order (Joomla)
- Fix dropdowns in customizer mode (WordPress)

## 1.2.1 (December 07, 2016)

### Fixed

- Fix WordPress 4.7 incompatibility issue
- Fix check for API key (Joomla)
- Fix featured article parameters (Joomla)
- Fix title on edit modules and menu items modal (Joomla)

## 1.2.0 (December 05, 2016)

### Added

- Add Max style
- Add layout options for blog and posts
- Add title options for modules/widgets
- Add headline style line to elements
- Add preserve color option for primary and secondary sections
- Add x-large padding to section options
- Launch page builder from article edit (Joomla)
- Add dashboard quickicon for website builder (Joomla)
- Add front-end editing if page builder is active (Joomla)

### Changed

- Refactored system pages and modules/widgets
- Split element component into divider, heading and link component
- Primary and secondary sections now adapt the text color automatically
- page builder button opens builder directly (WordPress)
- Mark menu-item as active if current page is a sub-page of the menu-item (WordPress)

### Fixed

- Fix sidebar wrapping if grid gutter is none
- Fix element settings if previously saved empty
- Fix WooCommerce styling
- Fix module blank style
- Resetting style variables shows save button
- Fix widget selection in builder (WordPress)
- Fix missing alt attribute for intro images (Joomla)
- Fix CodeMirror overflow in front-end editing (Joomla)

## 1.1.6 (November 24, 2016)

### Changed

- Set form radio background to transparent in material UIkit style

### Fixed

- Fix icon ratio and color in list element
- Fix scroll behaviour on page refresh
- Fix Map element (WordPress)
- Fix Navbar item options (WordPress)

## 1.1.5 (November 21, 2016)

### Added

- Add max width option for grids in the builder
- Add option to open links in new window for all list elements
- Add smooth scrolling to links with URL fragments for all elements
- Add sidebar.php layout file (required by WooCommerce)
- Add icon alignment option to button element
- Add icon option to list element
- Add link muted as style option to button element
- Make builder element templates overridable in child-theme (/builder/{element}/template.php)

### Changed

- Update Bootstrap layer (Joomla)

### Fixed

- Make transparent header work with section styles
- Remove possible horizontal scrollbar during animations
- Fix font-smoothing after animation for Webkit
- Fix textarea border for minimal style
- Fix z-index issue for mobile dropdown menu
- Ignore compression setting in customizer mode
- Access check on builder page (Joomla)
- Save button on Menu/Module edit (Joomla)
- Navbar item options (WordPress)
- Fix asset urls within installations using custom ports (WordPress)

## 1.1.4 (November 15, 2016)

### Added

- Clear cache button
- Pass through video url parameters (video element)

### Changed

- Saving a layout won't show the builder's save button
- Show default social icon if service is unknown

### Fixed

- Element sorting in builder
- Menu widgets (WordPress)

## 1.1.3 (November 11, 2016)

### Fixed

- Fix theme updater (WordPress)

## 1.1.2 (November 11, 2016)

### Fixed

- Fix builder on empty article (Joomla)

## 1.1.1 (November 10, 2016)

### Added

- "New" button for menu items and modules (Joomla)

### Changed

- Update Google Fonts list
- Optimize drag style for builder elements
- Optimize changelog style

### Fixed

- Fix element animations delay
- Fix image element svg width/heigth
- Fix custom class on module/widget element
- Fix child themes (WordPress)
- Fix duplicating social icons bug (WordPress)
- Fix builder output when cache enabled (Joomla)

## 1.1.0 (November 07, 2016)

### Added

- Add WordPress support
- Add grid element to builder
- Apply content plugins/shortcodes to Builder output

### Fixed

- Fix builder element default values
- Fix click behaviour on item links in navbar
- Fix builder row layout edit
- Fix iconnav test

## 1.0.11 (November 02, 2016)

### Fixed

- Fix regression with Google fonts

## 1.0.10 (November 02, 2016)

### Added

- Add Horizon style
- Add fixed width option for grids
- Add decorative line for navbar items
- Add divider small style
- Add border mode top, left and right to style components
- Add box-shadow option to form and offcanvas
- Add more style options to blockquote and card badge

### Changed

- Improved variables ordering in style customizer
- Improved preview loading

### Fixed

- Customizer "cancel" no longer resets builder changes
- Make heading bullet work with text align
- Fix initial missing Google font variant and language settings
- Fix section default values
- Fix "Export variables" button disabled state
- Scrollspy animation classes are no longer applied to the row
- Joomla Module element respects its module's settings

## 1.0.9 (October 26, 2016)

### Added

- Add large option to section width
- Add xl alignment breakpoint for text align in elements

### Fixed

- Fix administration style
- Fix error on closing panel with editor, before value is set
- Fix always respect blog columns setting, preventing full-width articles
- Fix populate image alt attribute with filename if no alt text is set
- Fix missing inverse style options for all components
- Respect blog columns to prevent full width articles at the end
- HTML tags will no longer be stripped from footer elements

## 1.0.8 (October 21, 2016)

### Changed

- Significant speed improvement for style customizer

### Fixed

- Catch error with builder element
- Fix cancel button behaviour

## 1.0.7 (October 20, 2016)

### Added

- Add box shadow options to style components
- Add 100% width option in module template settings

### Changed

- Move image/video options for sections into their own panel
- Optimized box-shadow picker
- Optimized preview loading

### Fixed

- Fix default values in builder elements
- Fix variable names in style customizer
- Center social icons in header modal
- Fix builder toolbar (Safari 10)
- Fix page class output
- Fix .row-striped styling (Bootstrap)
- Fix css minification

## 1.0.6 (October 19, 2016)

### Changed

- Wrap custom js code in try/catch block
- Optimize variable groups in style
- Rename search border radius variable

### Fixed

- Update UIkit components after css injection in style preview panel
- Fix style group ordering
- Fix media manager in debug mode (Joomla)
- Fix text color option if navbar is transparent
- Fix UIkit tests if boxed page layout is set

## 1.0.5 (October 18, 2016)

### Added

- Add border and typo options to style components
- Add border mode to style components
- Add background and border options to slidenav and totop style component
- Allow for css/custom.css in child theme
- Allow to add style via child themes

### Changed

- Minify theme CSS
- Load minified UIkit
- LESS updates accordion to UIkit
- Optimize UIkit tests
- Optimize variable groups in style
- Rename subnav, tab and breadcrumb item variables

### Fixed

- Prevent background repeat for section images
- Fix card media border-radius
- Expand main section to fill the viewport if needed
- Set default style to minimal
- Load Bootstrap framework
- Builder row layout select (Safari 10)
- Template module positions preview (tp=1)
- Fix Bootstrap input resets
- Fix CodeMirror style

## 1.0.4 (October 12, 2016)

### Fixed

- Fix minor UI issues
- Fix image indicator if color field is none

## 1.0.3 (October 11, 2016)

### Fixed

- Fix style font picker
- Fix child theme select
- Fix preview box-shadow
- Fix temporarily preview scrollbars in Firefox
- Prevent builder being active on offline login
- Prevent modules/menu items stay checked out after edit

## 1.0.2 (October 11, 2016)

### Fixed

- Fix modules rendering bug
- Fix offline mode in preview

## 1.0.1 (October 10, 2016)

### Fixed

- Fix Joomla URI handling

## 1.0.0 (October 10, 2016)

### Added

- Initial release
