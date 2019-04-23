# Get Points In Specified City

Returns points for specified city name.

**URL** : `/api/v1/points/in/:city`

**Method** : `GET`

**Auth required** : YES

**Data constraints**

```json
{
    ":city": "[city name: must be a latin string that contain capitalized first character]",    
    "limit": "[integer, default 50]",
    "offset": "[integer, default 0]"    
}
```

**Data example** Partial data is allowed.


```json
{
    ":city": "Perm"
}
```

## Success Response

**Code** : `200 OK`

**Content example** :

```json
{
    "success": true,
    "data": [
        {

            "name": "office",
            "desc": "my office",
            "lat": 58.001833,
            "lon": 56.295739            
        }
    ]
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
        "city": "Attribute city must be valid city name."
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
      "Points search error"
    ]
}
```