# Python_Project_Admin_Login

This project was developed as a Python login and registration program, allowing only users with a specific code key to apply for register.

Users can register and log into the system, while the admin communicates with the backend server to manage user data and code data.

### TECHNOLOGY USED:

1. **PyQt** - Used for the entire graphical user interface.
2. **Requests** - Used for handling HTTP requests to the server for user signup, login, and seat management.
3. **JSON** -Utilized for data exchange between the application and the server.


FEATURES:

When a user logs in, the program checks the expiration date (expired_date) of the registered code and compares it with the current date. If the expiration date has passed, the account will no longer be able to log in. The user table is implemented to collect the last login time and the connection IP address.

In the License table of the database, you can check the validity period and the creation date of the code. The expired_date will change to "used" once the user utilizes the code, and the expiration date will be displayed by adding the validity period of the code to the current date.

Administrators can conveniently issue code keys for periods of 7/ 15/ 30 days through a dedicated administrator program.

# SCREENSHOTS
![asdasd](https://github.com/user-attachments/assets/d43792bd-c382-4520-a7b8-833303ff6e1f)
https://www.youtube.com/watch?v=380iYr0QxDQ
