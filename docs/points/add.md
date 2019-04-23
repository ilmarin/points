# Add Point Info

Allow add point.

**URL** : `/api/v1/points`

**Method** : `POST`

**Auth required** : YES

**Data constraints**

Provide point data.

```json
{
    "lat": "[correct latitude]",
    "lon": "[correct longitude]",
    "name": "string 255 characters max",
    "desc": "string 4000 characters max",
    "city": "[city name: must be a latin string that contain capitalized first character]"
}
```

**Data examples**

All fields required.

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

## Or

**Condition** : If exception generated during request, e.g. database exception.

**Code** : `500 INTERNAL SERVER ERROR`

**Content example** :

```json
{
    "success": false,
    "errors": [      
      "Point creation error."     
    ]
}
```