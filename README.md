# 🎓 Google Classroom Clone

Hello Team! 👋 Welcome to our Google Classroom Clone project. 

This file is to help everyone understand what work has been done in this folder so far. We are building this project using **Simple PHP**, **MySQL** for the database, and **CSS** for the design.

---

## 🚀 What We Have Done So Far

Here is a simple list of things that are ready:

### 1. 🗄️ Database (`db.sql`)
We have created the database file which contains the tables we need.
**Tables we have:**
- **`users`**: To save the accounts of students and teachers.
- **`classes`**: To save the details of each class (like class name, subject, and class code).
- **`assignments`**: To save the homework given by teachers.
- **`submissions`**: To save the work submitted by students.

### 2. 🔌 Database Connection (`config.php`)
This file connects our PHP code to our local MySQL database. It is already set up for XAMPP.

### 3. 🖥️ User Interface & Design (`index.php` & `style.css`)
We have designed the main dashboard so it looks just like the real Google Classroom!
- **Separated Files:** We put the top menu in `includes/navbar.php` and the side menu in `includes/sidebar.php`. This makes our main code much cleaner and easier to understand.
- **Design (`style.css`):** We used nice fonts and colors. The class cards even have a nice effect when you move your mouse over them.
- **Dummy Data:** Right now, the classes you see on the screen (like *Web Engineering*) are just dummy data to show how the design looks. Later, we will bring real classes from our database.

---

## 🛠️ Languages We Are Using

- **Backend:** PHP
- **Database:** MySQL
- **Frontend:** HTML and CSS

---

## ⚙️ How You Can Run This on Your Laptop

If you want to see this project running on your own computer, just follow these simple steps:

1. **Open XAMPP**: Start both **Apache** and **MySQL** services.
2. **Setup the Database**:
   - Open your browser and go to `http://localhost/phpmyadmin`.
   - Create a new database and name it `classroom_db`.
   - Click on the **Import** tab at the top.
   - Choose the `db.sql` file from this folder and import it.
3. **Run the Project**:
   - Make sure this whole project folder (`Uni-Team-Project`) is inside your XAMPP `htdocs` folder.
   - Open your browser and go to: [http://localhost/Uni-Team-Project/google-classroom-clone/](http://localhost/Uni-Team-Project/google-classroom-clone/)

---

✨ *If anyone has questions, just ask! Let's complete this project together!* ✨