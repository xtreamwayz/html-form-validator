The form validator detects HTML5 form elements and adds default validators depending on the used attributes.

## The input element

### Hidden state (type=hidden)

*Attributes:*
[[value|API Attributes#value]].

```html
<input type="hidden" name="element_name" value="" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#hidden-state-(type=hidden))

### Text state (type=text) and Search state (type=search)

The difference between the Text state and the Search state is primarily stylistic.

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="text" name="name" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#text-(type=text)-state-and-search-state-(type=search))

### Telephone state (type=tel)

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="tel" name="tel" data-country="es" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#telephone-state-(type=tel))

### URL state (type=url)

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="url" name="url" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#url-state-(type=url))

### E-mail state (type=email)

*Attributes:*
[[list|API Attributes#list]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[multiple|API Attributes#multiple]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="email" name="email" data-validator-use-mx-check="true" />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#e-mail-state-(type=email))

### Password state (type=password)

*Attributes:*
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="password" name="password" required />
```

Resources:
[whatwg](https://html.spec.whatwg.org/multipage/forms.html#password-state-(type=password))

### type=date

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="date" name="date" />
```

### type=month

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="month" name="month" />
```

### type=week

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="week" name="week" />
```

### type=time

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="time" name="time" />
```

### type=datetime-local

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="datetime-local" name="datetime-local" />
```

### type=number

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="number" name="number" min="1" max="5" />
```

### type=range

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="range" name="range" min="1" max="10" step="2" />
```

### type=color

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="color" name="color" />
```

### type=checkbox

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="checkbox" name="checkbox" value="value" />
```

### type=radio

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="radio" name="gender" value="male" /> Male<br />
<input type="radio" name="gender" value="female" /> Female<br />
<input type="radio" name="gender" value="other" /> Other
```

### type=file

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="file" name="file" />
```

### type=image

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<input type="image" name="element_name" value="" />
```

## The select element

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<select name="car">
    <option value="volvo">Volvo</option>
    <option value="saab">Saab</option>
    <option value="mercedes">Mercedes</option>
    <option value="audi">Audi</option>
</select>
```

## The textarea element

*Attributes:*
[[list|API Attributes#list]],
[[max|API Attributes#max]],
[[min|API Attributes#min]],
[[step|API Attributes#step]],
[[maxlength|API Attributes#maxlength]],
[[minlength|API Attributes#minlength]],
[[pattern|API Attributes#pattern]],
[[disabled|API Attributes#disabled]],
[[readonly|API Attributes#readonly]],
[[required|API Attributes#required]],
[[value|API Attributes#value]].

```html
<textarea name="textarea"></textarea>
```
