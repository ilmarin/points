# Get Points By Desired Radius And IP Address

Returns points for specified radius and IP address.

**URL** : `/api/v1/points/inrad`

**Method** : `GET`

**Auth required** : YES

**Data constraints**

```json
{
    "rad": "integer, radius in meters (default 1000), 10000 max"
    "ip": "valid IP address (optional)"
}
```

**Data example** Partial data is allowed.


```json
{
    "rad": 10000,
    "ip": 127.0.0.1
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
            "lon": 56.295739,
            "in": "Perm"
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
        "ip": "Attribute ip must be valid ip address."
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