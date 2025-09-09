## Developer Informations
### Changelogs
These changelogs are basically just a summary list of changes that are very important for developers information.

#### UPDATE Version 1.25.9.09 [ Last update: 09/09/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed some form-validation's codes logic

**[ DATABASE ]**
- Added `is_active` database-field on users' database

**[ UI/UX ]**
- Added failed login if user is not activated
- Fixed `useridnumber` is not in uppercase if failed to login
- Fixed input-wrapper element by adding checked attribute for checkbox input-type
- Fixed checkbox style

**[ LARAVEL ]**
- Fixed some codes

</details>

#### UPDATE Version 1.25.9.04 [ Last update: 09/04/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Added form confirmation before delete academics data

**[ UI/UX ]**
- Fixed some table columns' width
- Fixed navigator-buttons not display correctly if the data is empty
- Fixed overflow-x on main-content if the side navigation-bar is active

**[ LARAVEL ]**
- Added function `GetById` on AcademicController controller
- Added function `GetById` on LetterController controller
- Added route to delete academic's data
- Fixed academics are no longer removable if already accepted
- Fixed schedule page now is using latest controllers, databases, and codes logic
- Fixed search by academic type on schedule page now is using by existed data
- Fixed delete academic now is enabled
- Fixed roomname not sorted alphabetically
- Fixed codes logic on navigator-buttons to prevent overflow button page
- Fixed some codes
- Removed unused codes

</details>

#### UPDATE Version 1.25.9.02 [ Last update: 09/02/2025 ]
<details>
<summary>Click to expand</summary>

`This update has so many changes. The fact about removing existed seminar and thesis defense (codes and files), is about codes and logics efficiency. Also to avoid duplicated data, system crash, bugs, and more in some cases.`

**[ COMMONS ]**
- Fixed some bugs

**[ DATABASE ]**
- Added Academic database<br>
`To replace existed separated seminar and thesis defense database`

**[ UI/UX ]**
- Added academics page
- Fixed side-navigationbar

**[ LARAVEL ]**
- Added AcademicContoller controller
- Added Academic model
- Added dynamic-language texts for some codes
- Fixed some codes on LetterContoller controller
- Fixed some codes on PageContoller controller
- Fixed some codes on RoomContoller controller
- Fixed `Message` function on HelperController controller to make dynamic route instead of only for redirect back
- Fixed default `fallback_locale` into Indonesia
- Fixed some routes
- Fixed typo
- Removed unused codes
- Removed unused files

</details>

#### UPDATE Version 1.25.9.01 [ Last update: 09/01/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed some inputs logic

**[ DATABASE ]**
- Fixed now `moderator` database-field is nullable
- Fixed typo
- Removed `supervisory_committee` that no longer needed

**[ UI/UX ]**
- Fixed dialog input-data top-banner is no longer scrolled
- Fixed dialog maximum height by reduced into 90%

**[ LARAVEL ]**
- Fixed request-query in better way for consistency
- Fixed `moderator` input-field is no longer available for Thesis Defense
- Fixed `moderator` field is not required anymore for Thesis Defense in LetterController controller
- Fixed typo
- Removed `supervisory_committee` that no longer needed
- Removed unused codes

</details>

#### UPDATE Version 1.25.8.29 [ Last update: 08/29/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Added form-confirmation before deleting user(s)
- Fixed some bugs when validating form(s)
- Fixed form-validation that button save or submit is no longer enabled if content(s) is/are not changed
- Fixed typo

**[ DATABASE ]**
- Fixed typo

**[ UI/UX ]**
- Fixed useridnumber-field now is readonly
- Fixed dialog-message width-size
- Fixed misplaced animations for dialog-message
- Fixed dialog letter-viewer max-width when on full-screen
- Renamed some images
- Removed unused codes

**[ LARAVEL ]**
- Added `Asset` function in HelperController controller
- Fixed DeterministicEncryption traits function by added inside the traits rather than included in every Model classes
- Fixed useridnumber now is no longer editable
- Fixed assets cache by added each assets dynamically
- Fixed some routes

</details>

#### UPDATE Version 1.25.8.28 [ Last update: 08/28/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed when adding user(s) and the password added automatically now is using uppercase (again)

**[ UI/UX ]**
- Fixed button(s) when focused
- Fixed visibility side-navigationbar to prevent clickable when not visible

**[ LARAVEL ]**
- Added Message function in HelperController controller
- Fixed some codes on LetterContoller controller
- Fixed some codes on RoomContoller controller
- Fixed some codes on UserContoller controller
- Fixed some routes

</details>

#### UPDATE Version 1.25.8.27 [ Last update: 08/27/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed when adding user(s) and the password added automatically now is no longer in uppercase by default instead using lowercase to prevent user-error when login

**[ UI/UX ]**
- Fixed typo

**[ LARAVEL ]**
- Fixed RoomContoller controller
- Fixed failed to update letter(s)
- Fixed some routes
- Fixed typo
- Removed unused files

</details>

#### UPDATE Version 1.25.8.26 [ Last update: 08/26/2025 ]
<details>
<summary>Click to expand</summary>

**[ DATABASE ]**
- Added Letter database

**[ UI/UX ]**
- Fixed loading-animations
- Fixed padding on side-navigationbar

**[ LARAVEL ]**
- Added LetterController controller
- Added Letter model
- Added some routes
- Fixed some codes announcements page
- Fixed some codes

</details>

#### UPDATE Version 1.25.8.25 [ Last update: 08/25/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed when adding user(s) the password not added automatically when input the useridnumber

**[ UI/UX ]**
- Fixed pop-up after created seminar or thesis defense by replaced into dialog pop-up instead of toast pop-up
- Fixed display re-preview letters by not using the same display when letters created in the first place
- Fixed schedule page on admin by added status/filter in the first column
- Fixed some codes
- Fixed typo

**[ LARAVEL ]**
- Added HelperController controller

</details>

#### UPDATE Version 1.25.8.21 [ Last update: 08/21/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Added tag link into seminar and thesis defense page in dialog message

**[ UI/UX ]**
- Added animations for some elements
- Added star character (*) after label for each required-input elements
- Added dialog message called via server-side
- Fixed tag `<a>` not displayed properly in dialog pop-up
- Fixed some tables not displayed properly
- Fixed some bugs on side-navigationbar
- Removed unused codes

**[ LARAVEL ]**
- Added server-side validation for some actions

</details>

#### UPDATE Version 1.25.8.20 [ Last update: 08/20/2025 ]
<details>
<summary>Click to expand</summary>

**[ UI/UX ]**
- Fixed visibility side-navigationbar by added box-shadow
- Fixed adjustments some tables
- Fixed some codes

</details>

#### UPDATE Version 1.25.8.19 [ Last update: 08/19/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Added validation-forms to validate required input(s) before submit

**[ UI/UX ]**
- Added dialog pop-up animation
- Fixed background for button when disabled
- Fixed textarea element now is able to resize vertically
- Fixed some bugs when dialog message not close or display correctly
- Fixed some bugs
- Fixed typo

**[ LARAVEL ]**
- Fixed seminar and thesis defense are no longer removable if status is accepted
- Fixed some data is no longer stored dynamically to prevent inconsistency data<br>
`The example for this update is like when store the letter, in the supervisor column, the stored data is just plain text instead of id for the supervisor then get the rest of supervisor's data from database. This can make data more consistent if we want to look at in future time.`

</details>

#### UPDATE Version 1.25.8.15 [ Last update: 08/15/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed dialog input-data by reducing function's parameters
- Fixed typo

**[ UI/UX ]**
- Added select2 element
- Added admin-admins page
- Fixed when try to login but failed useridnumber will not be reset anymore
- Fixed overflowed sub-buttons on side-navigationbar
- Fixed side-navigationbar to reduce motion sickness by enabling overlap when hovered
- Fixed loading-animations to reduce motion sickness by changing the opacity instead of display-type
- Fixed some codes by removing unused codes and moved into global CSS class
- Fixed typo
- Fixed some codes

**[ LARAVEL ]**
- Added some functions in PageController controller
- Added some functions in UserController controller
- Added some routes
- Fixed overflowed to display and sent to the server on student dashboard page
- Fixed some codes logic on registrationform page
- Fixed codes logic on SeminarController controller
- Fixed codes logic on ThesisdefenseController controller
- Fixed typo

</details>

#### UPDATE Version 1.25.8.14 [ Last update: 08/14/2025 ]
<details>
<summary>Click to expand</summary>

**[ UI/UX ]**
- Fixed visibility on scrollbar element
- Fixed letter-viewer element's height not based on maximum content-height
- Fixed letter's content not centered in the letter-viewer element

**[ LARAVEL ]**
- Fixed when refresh the registrationletter page it gives error but now will redirected into dashboard instead
- Removed unused codes

</details>

#### UPDATE Version 1.25.8.13 [ Last update: 08/13/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Added side-navigationbar latest state using jQuery code instead manually server-side

**[ DATABASE ]**
- Added Room database
- Fixed some codes

**[ UI/UX ]**
- Added side-navigationbar room menu
- Fixed some codes
- Fixed some layouts and elements
- Fixed letter element for dynamic get room's name
- Updated CSS elements
- Updated button element as needed
- Updated nav-link element as needed

**[ LARAVEL ]**
- Added RoomController controller
- Added Room model
- Added some functions in PageController controller
- Added rooms' routes
- Fixed typo
- Fixed rooms are now get the data from database instead predefined text
- Fixed page queries in PageController controller by validated first
- Fixed queries logic in RoomController controller
- Fixed queries logic in SeminarController controller
- Fixed queries logic in ThesisdefenseController controller
- Fixed queries logic in UserController controller
- Removed unused codes
- Removed unused files

</details>

#### UPDATE Version 1.25.8.12 [ Last update: 08/12/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed when selected type not reset the page query
- Fixed dialog closed when hit "Enter" key
- Fixed dialog must close when clicked the outside of the dialog
- Removed unused codes

**[ UI/UX ]**
- Added animation for the input element when loading
- Fixed input-wrapper element

**[ LARAVEL ]**
- Fixed request query "type" logic
- Fixed when open the dialog comment got a bit of delay
- Fixed page-pagination by adding separated function in PageController controller
- Removed unused files
- Removed unused codes

</details>

#### UPDATE Version 1.25.8.11 [ Last update: 08/11/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Added function DialogInputData on blade file instead saparated JavaScript file
- Removed unused codes

**[ DATABASE ]**
- Fixed unnecessary columns to be encrypted both on seminar and thesis defense table

**[ UI/UX ]**
- Fixed lecturers table

**[ LARAVEL ]**
- Added DeterministicEncryption traits for better performance-encryption rather than default built-in encryption
`This allows to make data being stored in database encrypted but still easy to use again and make performance better`
- Added new routes to get comments on specific id
- Added Deterministic encryption in UserContoller controller for some data
- Fixed authentication warning status on login page
- Fixed data sent into announcements page
- Fixed data username not sent into schedule page
- Fixed some codes in SeminarController controller
- Fixed some codes in ThesisdefenseController controller
- Fixed data-input is called via client-side (again) instead of server-side to improve experience
- Removed unused codes

</details>

#### UPDATE Version 1.25.8.06 [ Last update: 08/06/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed when closing dialog-input not to resetting query type

**[ UI/UX ]**
- Added announcements page
- Added menu for announcements
- Fixed padding-top on side-navigationbar
- Fixed arrow button-list on side-navigationbar not on the right of the parent button
- Fixed text alignment on the tables
- Fixed dialog announcements form
- Fixed students table

**[ LARAVEL ]**
- Added some functions in PageController controller
- Added some routes
- Fixed error when trying to search in schedule page because of get usernames of null

</details>

#### UPDATE Version 1.25.8.06 [ Last update: 08/06/2025 ]
<details>
<summary>Click to expand</summary>

**[ LARAVEL ]**
- Fixed data parsed on classes rather than on each pages
- Fixed when thesis defense updated admin not redirected into thesis defense's page but seminar's page instead
- Fixed some bugs
- Remove unused codes

</details>

#### UPDATE Version 1.25.8.06 [ Last update: 08/06/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed dialog input-data now is called via server instead plain-text using jQuery
- Fixed some bugs
- Removed unused codes

**[ UI/UX ]**
- Fixed GoogleDrive folder not opened in a new tab
- Fixed useridnumber not displayed uppercase via server-side
- Fixed some bugs

**[ LARAVEL ]**
- Added some functions in SeminarController controller
- Added some functions in ThesisdefenseController controller
- Added some functions in UserController controller
- Added some routes
- Fixed memory overflow on some functions in UserController controller
- Fixed seminar's comment not deleted if status is accepted
- Fixed thesis defense's comment not deleted if status is accepted
- Fixed lecturers are now get the data from database instead predefined text
- Fixed some bugs

</details>

#### UPDATE Version 1.25.8.05 [ Last update: 08/05/2025 ]
<details>
<summary>Click to expand</summary>

**[ UI/UX ]**
- Added admin dashboard page

**[ LARAVEL ]**
- Updated PageController controller for admin dashboard page

</details>

#### UPDATE Version 1.25.8.04 [ Last update: 08/04/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Fixed typo
- Moved inline jQuery script(s) into separated function(s)

**[ DATABASE ]**
- Fixed some type of column in User database

**[ UI/UX ]**
- Added CSS elements
- Added navigation-buttons element
- Added admin-students page
- Added admin-lecturers page
- Added admin-seminars page
- Added admin-thesisdefenses page
- Added some functions in PageController controller
- Added some functions in SeminarController controller
- Added some functions in ThesisdefenseController controller
- Added some functions in UserController controller
- Fixed inconsistency margin-top in login-page
- Fixed tag `<a>` not shown properly
- Fixed admin navigation-sidebar
- Fixed some bugs
- Removed unused codes
- Moved schedule page into commons folder

**[ LARAVEL ]**
- Added encrypted data in User model
- Added user-role middleware
- Added middleware class for each user-role
- Added some routes
- Fixed codes logic
- Fixed code indentation and standardization
- Fixed redirected page into desired dashboard instead of default laravel after logged-in
- Removed unused files
- Removed unused codes

</details>

#### UPDATE Version 1.25.7.31 [ Last update: 07/31/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Updated UpdateQueryParam by added ResetParams to reset unnecessary parameter(s)

**[ DATABASE ]**
- Added Thesis Defense database

**[ UI/UX ]**
- Added CSS elements
- Added toast pop-up
- Added "oninput", "onchange", and "autofocus" attribute on input element and fixed some bugs when element added a new slot inside it
- Updated Seminar and Thesis Defense Flow image
- Updated login page
- Updated schedule page
- Fixed some bugs
- Fixed animations' path

**[ LARAVEL ]**
- Added PageController controller
- Added Thesisdefense model
- Added ThesisdefenseController controller
- Added UserController controller
- Updated routes by added classes and queries
- Fixed users are not redirected to the desired dashboard
- Fixed type page not as expected
- Fixed schedule page gives infinite loop if the semester is not available on the database
- Fixed letter element dynamically get username from database instead of just get current user session
- Fixed some bugs
- Renamed DateIndoFormatter into DateIndoFormatterController
- Removed unused codes

</details>

#### UPDATE Version 1.25.7.29 [ Last update: 07/29/2025 ]
<details>
<summary>Click to expand</summary>

**[ DATABASE ]**
- Fixed Seminar database for encrypted data

**[ UI/UX ]**
- Added CSS elements
- Added letter element
- Added "href" and "target" attribute on button element that acts like from tag `<a>`
- Fixed some bugs
- Updated requirements page

**[ LARAVEL ]**
- Added routes for registrationform and requirements
- Updated routes for dashboard with the needed method
- Updated Seminar model by enabling encrypted data
- Updated Seminar controller into usable function for showing page, creating, updating, deleting database, and so on
- Fixed dashboard page into active page (not a static page anymore)
- Removed unused codes

</details>

#### UPDATE Version 0.0.1.03 [ Last update: 07/28/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Updated PDF-viewer must be reset to default zoom before download it

**[ DATABASE ]**
- Added Seminar database

**[ UI/UX ]**
- Added registrationletter page
- Added activated navbar-button on registrationletter page
- Added letter-viewer element
- Fixed loading-text name based on app name
- Fixed default background button to none instead of white
- Fixed pages' padding when auto scroll engaged
- Fixed navigation button not activated on some pages

**[ LARAVEL ]**
- Added DateIndoFormatter controller
- Added registrationletter route
- Added Seminar model
- Added Seminar controller
- Fixed registration-form routes as what they should be
- Fixed typo
- Removed unused codes

</details>

#### UPDATE Version 0.0.1.02 [ Last update: 07/25/2025 ]
<details>
<summary>Click to expand</summary>

**[ DATABASE ]**
- Updated database migrations as needed

**[ UI/UX ]**
- Added CSS elements
- Added ipb-logo element
- Added input-wrapper element
- Added nav-link-dropdown element
- Added some colors
- Added dashboard page
- Added registration-form page
- Added flow page
- Added requirements page
- Added schedule page
- Updated app-layout as needed
- Updated login page as needed
- Updated register page as needed
- Updated button element as needed
- Updated nav-link element as needed
- Fixed some bugs
- Remove unused codes

**[ LARAVEL ]**
- Added routes
- Updated User model as needed
- Updated CreateNewUser as needed
- Updated AppLayout as needed
- Updated GuestLayout as needed
- Updated UserFactory as needed

</details>

#### UPDATE Version 0.0.1.01 [ Last update: 07/24/2025 ]
<details>
<summary>Click to expand</summary>

**[ COMMONS ]**
- Added README.md

**[ UI/UX ]**
- Added animation elements
- Added CSS elements
- Added images
- Added JavsScript elements

**[ LARAVEL ]**
- Added Laravel Framework
- Fixed code indentation and standardization
- Removed unused files

</details>