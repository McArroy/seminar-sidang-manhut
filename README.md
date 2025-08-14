## Developer Informations
### Changelogs
These changelogs are basically just a summary list of changes that are very important for developers information.

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
- Fixed dialog input-data is now called via server instead plain-text using jQuery
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