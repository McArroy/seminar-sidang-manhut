## Developer Informations
### Changelogs
These changelogs are basically just a summary list of changes that are very important for developers information.

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