<h2>Register</h2>

<form method="post">
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>

    <select name="role">
        <option value="user">User</option>
        <option value="faculty">Faculty</option>
    </select><br><br>

    <button type="submit">Register</button>
</form>

<a href="/auth/login">Back to login</a>
