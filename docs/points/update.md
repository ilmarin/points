# Update point

Update the Point information.

**URL** : `/api/v1/points/:pk`

**Method** : `PUT`

**Auth required** : YES

**Data constraints**

```json
{
    "id": "[integer]",
    "lat": "[correct latitude]",
    "lon": "[correct longitude]",
    "name": "string 255 characters max",
    "desc": "string 4000 characters max",
    "city": "[city name: must be a latin string that contain capitalized first character]"
}
```

**Data example** Partial data is allowed.


```json
{
    "lat": 58.001833,
    "lon": 56.295739,   
    "name": "office",
    "desc": "my office",
    "city": "Perm"
}
```

## Success Response

**Condition** : Update can be performed either fully or partially.

**Code** : `200 OK`

**Content example** :

```json
{
    "success": true
}
```

## Error Response

**Condition** : If provided data is invalid, e.g. a name field is too long.

**Code** : `400 BAD REQUEST`

**Content example** :

```json
{
    "success": false,
    "errors": {
      "validation": {
        "lat": "Attribute lat must be correct latitude."
      }      
    }
}
```

### Or

**Condition** : If exception generated during request, e.g. database exception.

**Code** : `500 INTERNAL SERVER ERROR`

**Content example** :

```json
{
    "success": false,
    "errors": [      
      "Point updating error."     
    ]
}
```