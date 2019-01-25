# Instant IDE Change Log

https://cobaltapps.com/downloads/instant-ide-manager-plugin/

## [1.1.7] - 2019-01-24
### Added
- Added current site URL to title tag to make it easier to identify multiple sites being edited in the same browser.

### Changed
- Updated both the Ace and Monaco Editors to their latest versions.

### Fixed
- Fixed file tree LI styling issue (added inline-block to CSS).
- Fixed bug where downloading a zip file resulted in a .zip.zip file extension.

## [1.1.6] - 2018-09-24
### Changed
- Removed the max-width editor style that was preventing the menu bar from displaying full width on wide displays.
- Increased the Ace Editor font size from 12px to 14px for better visibility.
- Removed obfuscated JS code from console template file that keeps getting flagged as malicious code.

## [1.1.5] - 2018-04-18
### Changed
- Changed obfuscated console script minification to more traditional minification to prevent triggering virus alerts from WP firewalls when iIDE Manager is installed.
- Wrapped HTTP_USER_AGENT in !empty() code to prevent potential PHP errors on certain server/firewall configurations.

## [1.1.4] - 2018-02-16
### Added
- Added custom PHP session handling to help ensure that iIDE sessions remain active for full duration of "Remember Me" cookie.

## [1.1.3] - 2018-01-26
### Added
- Added require_once for contstnat to top of logout file as some setups…

### Changed
- Tweaked the instant_ide_url function to more accurately detect the correct URL protocol.
- Required iIDE Constants file at the top of the logout.php file to prevent potential logout errors.

## [1.1.2] - 2018-01-20
### Added
- Added October CMS installation support.
- Added .yaml file support.

### Changed
- Refined the install CMS functionality.
- Made it so the Site Preview iFrame "Preview URL" acts more like an Address Bar so you can manually set the iFrame address.
- Refined the .htaccess file to whitelist iIDE folders.

### Fixed
- Fixed bug where Site Preview tablet and mobile icons were not functioning properly.

## [1.1.1] - 2018-01-17
### Added
- Add "Set As Dev Path" and "Reset Dev Path" folder context menu options.

### Changed
- Set Site Preview iFrame to a set min-width of 1300px and added horizontal scrolling.

### Fixed
- Fixed typo in Options popup regarding Monaco Editor theme select menu.

## [1.1.0] - 2018-01-01
### Added
- Added one-click WordPress install functionality.
- Added right-click "Preview Folder" feature.
- Added site preview url view feature.

### Changed
- Upgraded SweetAlert script (js-based message pop-up script) to SweetAlert2.
- Improved file/folder create/rename functionality by incorporating SweetAlert2 name input feature.
- Added Firefox-specific styles to place file-tree cog icon in more idea position for vertical scrollbar.

### Fixed
- Added constant defined check in footer code to prevent pre-install PHP errors.

## [1.0.3] - 2017-12-22
### Fixed
- Tweaked session cookie parameters so they remain compatible with sub-folder WordPress installs.

## [1.0.2] - 2017-12-18
### Added
- Added ability to unzip files through double-click event or right-click contextmenu event.
- Added "Delete zip file after unzip" conditional popup message.
- Added file upload progress bar.

### Changed
- Added 'zip' file-type to file browser list-items for more precise double-click events.

### Fixed
- Fixed "file edited" tab icon spacing so it does not get pushed down when displayed.
- Fixed bug where duplicated and pasted/dropped files are not always properly identified for double-click events.

## [1.0.1] - 2017-12-15
### Changed
- Tweaked file tree HTML and CSS to "fix" file tree cog icon in place.
- Changed jQuery UI draggable "delay" code over to "distance" for better accidental drag prevention.

## [1.0.0] - 2017-12-12
### Added
- Initial release.


## Template for future logs. ##

## [9.0.0] - 2032-01-28
### Added
- Add example text.

### Changed
- Improve example text.

### Removed
- Remove example text.

### Fixed
- Fix example text.
