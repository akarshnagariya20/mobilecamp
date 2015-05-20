<?php if (!defined('APS_VER')) exit('restricted access');
/*
 * @package WordPress
 * @subpackage APS Products
*/
	// restore aps zoom settings
	function aps_default_zoom_settings() {
		$settings = array(
			'enable' => 1,
			'lensShape' => 'square',
			'lensSize' => 150,
			'lensBorder' => 1,
			'zoomType' => 'window',
			'scrollZoom' => 1,
			'easing' => 1,
			'responsive' => 1,
			'easingAmount' => 12,
			'zoomWindowWidth' => 400,
			'zoomWindowHeight' => 400
		);
		$success = update_option('aps-zoom', $settings);
		return $success;
	}
	
	// restore aps gallery settings
	function aps_default_gallery_settings() {
		$settings = array(
			'enable' => 1,
			'effect' => 'slideDown',
			'nav' => 1,
			'close' => 1
		);
		$success = update_option('aps-gallery', $settings);
		return $success;
	}
	
	// restore aps default design settings
	function aps_default_design_settings() {
		$design = array(
			'container' => '1200',
			'responsive' => '1',
			'content' => 'left',
			'skin' => 'skin-blue',
			'border' => 'border',
			'icons' => '1',
			'custom-css' => '0'
		);
		
		aps_generate_styles($design);
		$success = update_option('aps-design', $design);
		return $success;
	}
	
	// restore aps default main settings
	function aps_default_main_settings() {
		$settings = array(
			'index-page' => '',
			'index-title' => __('APS Products', 'aps-text'),
			'comp-page' => '',
			'comp-list' => '',
			'num-products' => 12,
			'product-slug' => 'product',
			'brands-dp' => __('Brands', 'aps-text'),
			'brands-sort' => 'a-z',
			'brand-slug' => 'brand',
			'filter-title' => __('Filters', 'aps-text'),
			'compare-slug' => 'comparison',
			'search-title' => __('Search Results for %term%', 'aps-text'),
			'brands-title' => __('%brand% Products', 'aps-text'),
			'more-title' => __('More Products from %brand%', 'aps-text'),
			'more-num' => 3,
			'rating-title' => __('Our Rating', 'aps-text'),
			'rating-text' => __('The overall rating is based on review by our experts', 'aps-text'),
			'user-rating-title' => __('Overall User\'s Rating', 'aps-text'),
			'user-rating-text' => __('The overall rating is based on %num% reviews by users.', 'aps-text'),
			'post-review-note' => __('Please not that each user review reflects the opinion of it\'s respectful author.', 'aps-text')
		);
		$success = update_option('aps-settings', $settings);
		aps_flush_rewrite_rules();
		return $success;
	}
	
	// restore aps default tabs settings
	function aps_default_tabs_settings() {
		$tabs = array(
			'overview' => array('name' => 'Overview', 'content' => 'overview', 'display' => 'yes'),
			'specs' => array('name' => 'Specs', 'content' => 'specs', 'display' => 'yes'),
			'reviews' => array('name' => 'Reviews', 'content' => 'reviews', 'display' => 'yes'),
			'gallery' => array('name' => 'Gallery', 'content' => 'gallery', 'display' => 'yes'),
			'videos' => array('name' => 'Videos', 'content' => 'videos', 'display' => 'yes'),
			'offers' => array('name' => 'Offers', 'content' => 'offers', 'display' => 'yes')
		);
		$success = update_option('aps-tabs', $tabs);
		return $success;
	}
	
	// restore aps default main features
	function aps_default_features() {
		$features = array(
			'feature1' => array('name' => 'CPU', 'icon' => 'cpu'),
			'feature2' => array('name' => 'RAM', 'icon' => 'ram'),
			'feature3' => array('name' => 'Storage', 'icon' => 'hdd'),
			'feature4' => array('name' => 'Display', 'icon' => 'display'),
			'feature5' => array('name' => 'Camera', 'icon' => 'camera'),
			'feature6' => array('name' => 'OS', 'icon' => 'cog')
		);
		$success = update_option('aps-features', $features);
		return $success;
	}
	
	// restore aps default groups settings
	function aps_default_groups() {
		$groups = array(
			'group1' => array('name' => 'General', 'icon' => 'tablet', 'display' => 'yes'),
			'group2' => array('name' => 'Design', 'icon' => 'art', 'display' => 'yes'),
			'group3' => array('name' => 'Network', 'icon' => 'tower', 'display' => 'yes'),
			'group4' => array('name' => 'Display', 'icon' => 'display', 'display' => 'yes'),
			'group5' => array('name' => 'Media', 'icon' => 'media', 'display' => 'yes'),
			'group6' => array('name' => 'Camera', 'icon' => 'camera', 'display' => 'yes'),
			'group7' => array('name' => 'Software', 'icon' => 'window', 'display' => 'yes'),
			'group8' => array('name' => 'Hardware', 'icon' => 'cog', 'display' => 'yes'),
			'group9' => array('name' => 'Connectivity', 'icon' => 'podcast', 'display' => 'yes'),
			'group10' => array('name' => 'Data', 'icon' => 'globe', 'display' => 'yes'),
			'group11' => array('name' => 'Messaging', 'icon' => 'mail', 'display' => 'yes'),
			'group12' => array('name' => 'Battery', 'icon' => 'battery', 'display' => 'yes')
		);
		$success = update_option('aps-groups', $groups);
		return $success;
	}
	
	// restore aps default groups settings
	function aps_default_attributes() {
		$attributes = array(
			'group1' => array(
				'device-type' => array('name' => 'Device Type', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'model' => array('name' => 'Model', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'announced' => array('name' => 'Announced', 'type' => 'date', 'display' => 'yes', 'info' => ''),
				'released' => array('name' => 'Released', 'type' => 'date', 'display' => 'yes', 'info' => ''),
				'status' => array('name' => 'Status', 'type' => 'select', 'display' => 'yes', 'info' => '', 'options' => array('Available', 'Discontinued', 'Coming Soon')),
				'price' => array('name' => 'Price', 'type' => 'text', 'display' => 'yes', 'info' => '')
			),
			'group2' => array(
				'type' => array('name' => 'Type', 'type' => 'select', 'display' => 'yes', 'info' => '<strong>Design Type</strong> called form factor refers to a mobile phone\'s size, shape, and style as well as the layout and position of major components of phone. There are three major form factors seen in mobile phones =>  bar phones, folding phones and sliding phones.', 'options' => array('Bar', 'Sliding', 'Folding')),
				'dimensions' => array('name' => 'Dimensions', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'weight' => array('name' => 'Weight', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'protection' => array('name' => 'Protection', 'type' => 'textarea', 'display' => 'yes', 'info' => ''),
				'colors' => array('name' => 'Colors', 'type' => 'textarea', 'display' => 'yes', 'info' => '')
			),
			'group3' => array(
				'2g-network' => array('name' => '2G Network', 'type' => 'textarea', 'display' => 'yes', 'info' => ''),
				'3g-network' => array('name' => '3G Network', 'type' => 'textarea', 'display' => 'yes', 'info' => ''),
				'4g-network' => array('name' => '4G Network', 'type' => 'textarea', 'display' => 'yes', 'info' => ''),
				'sim' => array('name' => 'SIM', 'type' => 'select', 'display' => 'yes', 'info' => '<strong>SIM</strong> (Subscriber Identity Module) is a small card that contains mobile network subscriber\'s account information. This allows the phone using the card to attach to a mobile network. The SIM card is most commonly associated with GSM and UMTS mobile networks. Moving a SIM card from one phone to another allows a subscriber to switch mobile phones without having to contact their mobile network carrier. SIM cards can also be used by a phone to store limited amounts of data, such as phone numbers and text messages.', 'options' => array('Standard SIM', 'Micro SIM', 'Nano SIM')),
				'dual-sim' => array('name' => 'Dual SIM', 'type' => 'text', 'display' => 'yes', 'info' => '')
			),
			'group4' => array(
				'type' => array('name' => 'Type', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Display Technology => </strong> A number of display technologies and types used in mobile phones =>  TFT (Thin Film Transistor), IPS (In-Place Switching), OLED (Organic Light Emitting Diode), AMOLED (Active-Matrix Organic Light-Emitting Diode), Super AMOLED (an even advanced version of AMOLED), Resistive Touchscreen (Resistive touchscreens contain two layer of conductive material with a very small gap between them which acts as a resistance), Capacitive Touchsceen (Capacitive touchscreen technology consists of a layer of glass coated with a transparent conductor)'),
				'size' => array('name' => 'Size', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'resolution' => array('name' => 'Resolution', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'colors' => array('name' => 'Colors', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Display Colors</strong> is refers to the number of different shades of colors that the screen is capable of displaying =>  64K colors, 256K colors and 16 million colors, Obviously 16M is highest available range of colors and better than others.'),
				'pixel-density' => array('name' => 'Pixel Density', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Pixel Density (PPI)</strong> is refers to the concentration of pixels on a particular display, measured in pixels per inch (ppi). Pixel density is calculated by dividing the diagonal pixel resolution of a display by its diagonal size, higher pixel density better display quality.'),
				'touch-screen' => array('name' => 'Touch Screen', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'protection' => array('name' => 'Protection', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Display Protection => </strong> Gorilla Glass is a special alkali-aluminosilicate glass shield with exceptional damage resistance that helps protect mobile displays from scratches, drops, and bumps of everyday use, It is always better to go for a smartphone with Gorilla Glass for that added protection and peace of mind.'),
				'features' => array('name' => 'Features', 'type' => 'textarea', 'display' => 'yes', 'info' => ''),
				'secondary-display' => array('name' => 'Secondary Display', 'type' => 'text', 'display' => 'yes', 'info' => '')
			),
			'group5' => array(
				'audio-playback' => array('name' => 'Audio Playback', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'video-playback' => array('name' => 'Video Playback', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'video-out' => array('name' => 'Video Out', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'fm-radio' => array('name' => 'FM Radio', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'alert-types' => array('name' => 'Alert Types', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'ring-tones' => array('name' => 'Ring Tones', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'loudspeaker' => array('name' => 'Loudspeaker', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'handsfree' => array('name' => 'Handsfree', 'type' => 'text', 'display' => 'yes', 'info' => '')
			),
			'group6' => array(
				'primary' => array('name' => 'Primary', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Camera</strong> is able to capture photographs and usually videos, The most important characteristics of a camera are the resolution (measured in megapixels), lens focus type (fixed or automatic), higher megapixel cameras are known to capture higher quality photos, but not always a good measurement of the photos quality.'),
				'image' => array('name' => 'Image', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'video' => array('name' => 'Video', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'features' => array('name' => 'Features', 'type' => 'textarea', 'display' => 'yes', 'info' => ''),
				'flash' => array('name' => 'Flash', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Flash Light => </strong> There is commonly two types of flash lights are used in camera mobile phones, LED Flash (LED flash offers lower power consumption with drive circuitry that takes up very little room, LEDs can be strobed faster than any other light source), Xenon Flash (xenon flash produces an extremely intense full-spectrum white light for a very short duration)'),
				'secondary' => array('name' => 'Secondary', 'type' => 'text', 'display' => 'yes', 'info' => '')
			),
			'group7' => array(
				'operating-system' => array('name' => 'Operating System', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>OS => </strong> Every computer system run on a base software called Operating System (OS). Operating System controls all basic operations of the computer (such as smartphone, PDAs, tablet computers and other handheld devices). The Operating System allows the user to install and run third party applications (apps), apps are used to add new functionality to the device.'),
				'user-interface' => array('name' => 'User Interface', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>UI</strong> or user interface of a device is the look and feel of the on-screen menu system. How it works, its color scheme, how it responds to button presses, all of these things are part of the user interface.'),
				'java-support' => array('name' => 'Java Support', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Java</strong> for Mobile Devices is a set of technologies that let developers deliver applications and services to all types of mobile handsets, ranging from price efficient feature-phones to the latest smartphones. Java is currently running on over 3 billion phones worldwide, and growing. It offers unrivaled potential for the distribution and monetization of mobile applications.'),
				'facebook' => array('name' => 'Facebook', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>Facebook</strong> is a popular free social networking website that allows registered users to create profiles, upload photos and video, send messages and keep in touch with friends, family and colleagues. The site is available in 37 different languages.'),
				'youtube' => array('name' => 'Youtube', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>Youtube</strong> is a popular free video-sharing website, Youtube is the largest video sharing site in the world, Millions of users around the world have created accounts on the site that allow them to upload videos that anyone can watch.')
			),
			'group8' => array(
				'chipset' => array('name' => 'Chipset', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Chipset</strong> is a group of integrated circuits designed to perform one or a more dedicated functions, often with real time computing constraints, Popular smartphones are equipped with more advanced embedded chipsets that can do many different tasks depending on their programming.'),
				'cpu' => array('name' => 'CPU', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>CPU</strong> (Central Processing Unit) mostly known as processors, CPU processes instructions in order to carry out certain functions that make your device operate properly. Processors are often described as the brain of computers, smartphones and tablets, Smartphones and tablets rely on processors to carry out their every task, Processors are an incredibly important factor in selecting any type of computing device, including your smartphone.'),
				'gpu' => array('name' => 'GPU', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>GPU</strong> (Graphics Processing Unit) is a single-chip processor designed to rapidly manipulate and alter memory to accelerate the creation of images in a frame buffer intended for output to a display, This includes things such as lighting effects, object transformations, and 3D motion.'),
				'ram-memory-' => array('name' => 'RAM (Memory)', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>RAM</strong> (Random Access Memory) is a type of computer memory that can be accessed randomly, any byte of memory can be accessed without touching the preceding bytes that allows information to be stored and accessed quickly from random locations. RAM is the most common type of memory found in computer systems, smartphones, tablets and other electronic devices.'),
				'internal-storage' => array('name' => 'Internal Storage', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Internal Storage</strong> is a data storage space (flash memory) mostly used in smartphones, tablets and other electronic devices where operating system, apps, music, photos, videos, files and other user data Is stored.'),
				'card-slot' => array('name' => 'Card Slot', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Memory Card Slot</strong> is a special slot for inserting a memory card. Memory cards allow you to expand the phone\'s built-in memory, A memory card (sometimes called a flash memory card or a storage card) is a small storage medium used to store data such as text, pictures, audio, and video, for use on small, portable or remote computing devices such as mobile phones, mp3 players, digital cameras.'),
				'sensors' => array('name' => 'Sensors', 'type' => 'textarea', 'display' => 'yes', 'info' => '<strong>Sensors</strong> are electronic components that detects and responds to some type of input from the physical environment. The specific input could be light, heat, motion, moisture, pressure and location, The output is generally a signal that is converted to use in computing systems, a location sensor, such as a GPS receiver is able to detect current location of your electronic device.')
			),
			'group9' => array(
				'bluetooth' => array('name' => 'Bluetooth', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Bluetooth</strong> is a wireless communications technology for exchanging data between mobile phones, headsets, computers and other network devices over short distances without wires, Bluetooth technology was primarily designed to support simple wireless networking of personal consumer devices.'),
				'infrared' => array('name' => 'Infrared', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>Infrared</strong> connectivity is an old wireless technology used to connect two electronic devices. It uses a beam of infrared light to transmit information and so requires direct line of sight and operates only at close range.'),
				'wi-fi' => array('name' => 'Wi-fi', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Wi-Fi</strong> is a popular wireless networking technology using radio waves to provide high-speed network connections that allows devices to communicate without cords or cables, Wi-Fi is increasingly becoming the preferred mode of internet connectivity all over the world.'),
				'wi-fi-hotspot' => array('name' => 'Wi-fi Hotspot', 'type' => 'check', 'display' => 'yes', 'info' => ''),
				'usb' => array('name' => 'USB', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'gps' => array('name' => 'GPS', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>GPS</strong> The Global Positioning System is a satellite-based radio navigation system, GPS permits users to determine their position, velocity and the time 24 hours a day, in all weather, anywhere in the world, In order to locate your position, your device or GPS receiver must have a clear view of the sky.'),
				'nfc' => array('name' => 'NFC', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>NFC</strong> (Near field communication) is a set of standards for smartphones and similar devices to establish peer-to-peer radio communications with each other by touching them together or bringing them into proximity, usually no more than a few inches.'),
				'hdmi' => array('name' => 'HDMI', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>HDMI</strong> (High-Definition Multimedia Interface) is a compact audio/video interface for transferring uncompressed video data and compressed or uncompressed digital audio data from a HDMI-compliant source device to a compatible computer monitor, video projector, digital television, or digital audio device.'),
				'wireless-charging' => array('name' => 'Wireless Charging', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Wireless Charging</strong> (Inductive Charging) uses an electromagnetic field to transfer energy between two objects. This is usually done with a charging station. Energy is sent through an inductive coupling to an electrical device, which can then use that energy to charge batteries or run the device.')
			),
			'group10' => array(
				'gprs' => array('name' => 'GPRS', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>GPRS</strong> (General Packet Radio Service) is a packet oriented mobile data service on the 2G and 3G cellular communication system\'s global system for mobile communications (GSM), Generally, GPRS is used for the purpose of wireless data transfer, such as sharing pictures and videos or browsing the Internet via a mobile phone connection.'),
				'edge' => array('name' => 'EDGE', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>EDGE</strong> (Enhanced Data GSM Environment) is a wireless network technology generally considered the next step in the 2G network offers data transfer rates up to four times faster than ordinary GSM networks, Generally, EDGE is used for the purpose of wireless data transfer, such as sharing pictures and videos or browsing the Internet via a mobile phone connection.'),
				'speed' => array('name' => 'Speed', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'web-browser' => array('name' => 'Web Browser', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Web Browser => </strong> a web browser is a software application used to locate, retrieve and display content on the World Wide Web, including Web pages, images, video and other files, The primary function of a web browser is to render HTML, the code used to design or markup webpages.')
			),
			'group11' => array(
				'sms' => array('name' => 'SMS', 'type' => 'select', 'display' => 'yes', 'info' => '<strong>SMS</strong> (Short Messaging Service) is a text messaging service component of phone, Web, or mobile communication systems. It uses standardized communications protocols to allow mobile phone devices to exchange short text messages over the networks.', 'options' => array('No', 'Yes', 'Yes (threaded view)')),
				'mms' => array('name' => 'MMS', 'type' => 'check', 'display' => 'yes', 'info' => '<strong>MMS</strong> (Multimedia Messaging Service) is a standard way to send messages that include multimedia content (audio clips, video clips and images) to and from mobile phones over wireless networks using the WAP protocol.'),
				'email' => array('name' => 'Email', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Email</strong> (Electronic Mail) is a system for receiving, sending, and storing electronic messages, Similar to a letter, email is text messages that may contain files, images, or other attachments sent via the internet to a recipient by using applications and software prograps. An email address is required to receive email, and that address is unique to the user.'),
				'im' => array('name' => 'IM', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>IM</strong> (Instant Messaging) is an exchange of text messages through a software application, it enable you to create a kind of private chat room with another individual in order to communicate in real time over the Internet.')
			),
			'group12' => array(
				'type' => array('name' => 'Type', 'type' => 'select', 'display' => 'yes', 'info' => '<strong>Battery Type => </strong> Cell phones run on various kinds of batteries depending on the manufacturer, phone size or shape and features. There are basically four types of cell phone batteries =>  Lithium Polymer, Lithium Ion, Nickel Metal Hydride and Nickel Cadmium.', 'options' => array('Li-Ion (Lithium Ion)', 'Li-Poly (Lithium Polymer)', 'NiCd (Nickel Cadmium)', 'NiMH (Nickel Metal Hydrid)')),
				'capacity' => array('name' => 'Capacity', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Battery Capacity</strong> is a measure (typically in Amp-hr) of the charge stored by the battery, and is determined by the mass of active material contained in the battery. The battery capacity represents the maximum amount of energy that can be extracted from the battery under certain conditions.'),
				'placement' => array('name' => 'Placement', 'type' => 'text', 'display' => 'yes', 'info' => ''),
				'standby' => array('name' => 'Standby', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Standby Time</strong> is the total amount of time that you can leave your is fully charged, turned on and ready to send and receive calls or data transmissions before completely discharging the battery.'),
				'talk-time' => array('name' => 'Talk Time', 'type' => 'text', 'display' => 'yes', 'info' => '<strong>Talk Time</strong> is the longest time that a single battery charge will last when you are constantly talking on the phone under perfect conditions, Ambient temperature and highly dependent on the cellular network environment such as the distance to the closest cell network tower.'),
				'music-play' => array('name' => 'Music Play', 'type' => 'text', 'display' => 'yes', 'info' => '')
			)
		);
		$success = update_option('aps-attributes', $attributes);
		return $success;
	}
	
	// restore aps default filters
	function aps_default_filters() {
		$filters = array(
			'cpu-type' => array('name' => 'CPU Type', 'slug' => 'cpu-type'),
			'cpu-speed' => array('name' => 'CPU Speed', 'slug' => 'cpu-speed'),
			'ram' => array('name' => 'RAM', 'slug' => 'ram'),
			'storage' => array('name' => 'Storage', 'slug' => 'storage'),
			'camera' => array('name' => 'Camera', 'slug' => 'camera'),
			'display-size' => array('name' => 'Display Size', 'slug' => 'display-size'),
			'os' => array('name' => 'Operating System', 'slug' => 'os')
		);
		$success = update_option('aps-filters', $filters);
		return $success;
	}

	// default review rating bars
	function aps_default_rating_bars() {
		$rating_bars = array(
			'design' => array(
				'label' => 'Design',
				'value' => 5,
				'info' => 'What do you feel about Design and build quality of this device?'
			),
			'display' => array(
				'label' => 'Display',
				'value' => 6,
				'info' => 'Screen size, image quality (brightness, colors, contrast, etc), visibility in sun light, angles of view and touchscreen.'
			),
			'camera' => array(
				'label' => 'Camera',
				'value' => 5,
				'info' => 'Usability of Camera interface, image and video quality in different light conditions and environment.'
			),
			'multimedia' => array(
				'label' => 'Multimedia',
				'value' => 6,
				'info' => 'Music and video players, playback quality, speaker sound and handsfree options.'
			),
			'features' => array(
				'label' => 'Features',
				'value' => 5,
				'info' => 'What do you feel about the features of this device, is these enough for you in everyday life?'
			),
			'connectivity' => array(
				'label' => 'Connectivity',
				'value' => 6,
				'info' => 'Bluetooth, Wi-fi connectivity, data trnasfer speed, internet browsing and interfaces.'
			),
			'call-quality' => array(
				'label' => 'Call Quality',
				'value' => 5,
				'info' => 'When you call someone, what is the voice quality you hear or vice versa, is speakerproduct working good during calls.'
			),
			'usability' => array(
				'label' => 'Usability',
				'value' => 6,
				'info' => 'What do you feel about usability and speed of UI (user interface), messaging, calling, contacts management, call history, notifications and internet browsing?'
			),
			'performance' => array(
				'label' => 'Performance',
				'value' => 5,
				'info' => 'Is this device perform good while watching videos, playing games, taking snapshots, browsing internet and navigate through other applications?'
			),
			'battery' => array(
				'label' => 'Battery',
				'value' => 6,
				'info' => 'What about battery life, while calling, listening music, watching videos, playing games and doing other tasks.'
			)
		);
		$success = update_option('aps-rating-bars', $rating_bars);
		return $success;
	}
	
	// restore aps default affiliates
	function aps_default_affiliates() {
		$affiliates = array(
			'amazon' => array(
				'name' => 'Amazon',
				'logo' => 'http://demo.webstudio55.com/arena/wp-content/uploads/amazon-logo.png'
			),
			'best-buy' => array(
				'name' => 'Best Buy',
				'logo' => 'http://demo.webstudio55.com/arena/wp-content/uploads/bestbuy-logo.png'
			)
		);
		$success = update_option('aps-affiliates', $affiliates);
		return $success;
	}
	
	// load default settings
	function aps_load_default_settings() {
		aps_default_main_settings();
		aps_default_design_settings();
		aps_default_gallery_settings();
		aps_default_zoom_settings();
		aps_default_tabs_settings();
		aps_default_affiliates();
		aps_default_groups();
		aps_default_attributes();
		aps_default_features();
		aps_default_rating_bars();
		// update total number of groups
		update_option('aps-num-groups', 12);
	}

	// place widgets in sidebar
	function aps_sidebar_widgets_setup() {
		$aps_sidebar = 'aps-sidebar';
		$widgets = array(
			'aps_search' => array(
				'title' => 'Search',
				'results' => 5
			),
			'aps_new_arrivals' => array(
				'title' => 'New Arrivals',
				'devices' => 6
			),
			'aps_comparisons' => array(
				'title' => 'Recent Compares',
				'devices' => 3
			),
			'aps_brands' => array(
				'title' => 'Brands',
				'nums' => 'yes'
			),
			'aps_top_products' => array(
				'title' => 'Top Rated',
				'devices' => 6
			)
		);
		$sidebar_widgets = get_option('sidebars_widgets');
		
		foreach ($widgets as $widget_name => $widget_data) {
			
			// get widget option
			$widget = get_option('widget_'.$widget_name);
			$widget = is_array($widget) ? $widget : array();
			$count = count($widget) + 1;
			
			// start adding widgets
			$sidebar_widgets[$aps_sidebar][] = $widget_name .'-' .$count;
			$widget[$count] = $widget_data;
			$widget = array('_multiwidget' => 1);
			
			// save widget data
			update_option('widget_' .$widget_name, $widget);
		}
		
		// update sidebar widgets option
		update_option('sidebars_widgets', $sidebar_widgets);
	}