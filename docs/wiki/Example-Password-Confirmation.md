```html
<form action="#" method="post">
    <input type="password" name="password" required />
    <input type="password" name="password-confirm" required data-validators="identical{token:password}" />
    <button type="submit">Submit</button>
</form>
```
