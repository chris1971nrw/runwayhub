# OpenAIP Integration Guide

**Published:** 2026-05-26  
**Category:** Tutorials  
**Tags:** openaip, aviation-data, integration

---

## 🌐 What is OpenAIP?

**OpenAIP** (Open Aviation Information Platform) is a free API providing real-time aviation data including:

- **Airports**: Runway data, frequencies, navaids
- **Waypoints**: Flight path waypoints
- **Airways**: Airways and routes
- **Navaids**: VOR, NDB, ILS stations
- **Charts**: AIP charts and NOTAMs

## 📡 Available Endpoints

### Core Endpoints (12 total)

| Endpoint | Description | Cache TTL |
|----------|-------------|-----------|
| `/airports` | All airports data | 5 min |
| `/airports/:iata` | Single airport by IATA | 5 min |
| `/airports/:icao` | Single airport by ICAO | 5 min |
| `/waypoints` | All waypoints | 5 min |
| `/waypoints/:id` | Single waypoint | 5 min |
| `/airways` | All airways | 5 min |
| `/airways/:id` | Single airway | 5 min |
| `/navaids` | All navaids | 5 min |
| `/navaids/:id` | Single navaid | 5 min |
| `/charts` | Available charts | 5 min |
| `/charts/:id` | Single chart | 5 min |
| `/notam` | NOTAMs | 5 min |

### Weather Endpoints (6 additional)

- Airport weather (METAR)
- Aviation weather alerts
- Gridpoint forecasts
- Wind forecasts
- Temperature forecasts
- Visibility forecasts

## 🔧 Integration in RunwayHub

RunwayHub implements OpenAIP with:

- **Caching**: 5-minute TTL to reduce API calls
- **Rate Limiting**: Automatic backoff on 429 errors
- **Database Storage**: Import data to local MySQL
- **API Endpoints**: 12 REST endpoints exposed
- **Fallback Handling**: Graceful degradation on errors

### Code Example

```php
$openAip = new \RunwayHub\Core\OpenAIP\Airport();

// Get Frankfurt Airport
$airport = $openAip->get('EDDF');

// Access data
echo $airport->name; // "Frankfurt"
echo $airport->iata; // "FRA"
echo $airport->icao; // "EDDF"
```

## 💾 Database Import

OpenAIP data is imported to your database:

### Tables Created

```sql
airports_openaip          -- Airport data
waypoints_openaip         -- Waypoint data
airways_openaip           -- Airways
navaids_openaip           -- Navaids
```

### Migration Commands

```bash
php artisan openaip:import:airports
php artisan openaip:import:waypoints
php artisan openaip:import:airways
php artisan openaip:import:navaids
php artisan openaip:sync
```

## ⚙️ Configuration

### Environment Variables

```bash
OPENAIP_API_KEY=your_api_key
OPENAIP_TIMEOUT=30
OPENAIP_CACHE_TTL=300
OPENAIP_BATCH_SIZE=100
```

### Custom Endpoints

Extend with custom endpoints in `src/core/OpenAIP/Client.php`:

```php
public function addEndpoint(string $name, string $url): self
{
    $this->endpoints[$name] = $url;
    return $this;
}
```

## 🎯 Use Cases

### Flight Planning

- Get airport data for flight plan creation
- Fetch waypoint coordinates
- Retrieve airway information

### Weather Monitoring

- Display airport weather
- Show METAR observations
- Display weather alerts

### Database Population

- Import complete AIP database
- Keep data current with sync cron
- Supplement with custom data

## 📊 Performance Tips

1. **Use Caching**: Always check cache before API call
2. **Batch Requests**: Group related API calls
3. **Set TTL Appropriately**: 5 min for weather, hourly for airport data
4. **Monitor Rate Limits**: Watch for 429 responses
5. **Database Optimization**: Index frequently queried fields

## 🔒 Security

### Best Practices

- **API Keys**: Never commit to git
- **Environment Variables**: Use `.env` file
- **Input Validation**: Sanitize all inputs
- **Rate Limiting**: Implement backoff logic
- **HTTPS Only**: Force encrypted connections

### Environment File

```env
OPENAIP_API_KEY={your_api_key_here}
OPENAIP_TIMEOUT=30
OPENAIP_CACHE_TTL=300
OPENAIP_BATCH_SIZE=100
OPENAIP_DEBUG=false
```

## 🛠️ Troubleshooting

### Common Issues

#### "API Key Invalid"

- Verify API key in OpenAIP dashboard
- Check environment variable is set
- Ensure no extra whitespace

#### "429 Too Many Requests"

- Check cache TTL settings
- Review rate limiting in code
- Consider reducing batch sizes

#### "Database Connection Failed"

- Verify MySQL credentials
- Check database exists
- Ensure correct charset (utf8mb4)

#### "Invalid Airport Code"

- Check ICAO/IATA format
- Airport not in OpenAIP database
- Verify spelling

## 📖 Related Documentation

- [Features Overview](/docs/features.md)
- [API Reference](/docs/api.md)
- [Database Schema](/docs/database.md)
- [Deployment Guide](/docs/deployment.md)

## 🤝 Contributing

Found a bug or want to add features?

1. Check existing issues
2. Create a new issue
3. Fork and submit PR
4. Follow coding standards

---

**Published:** 2026-05-26  
**Author:** RunwayHub Team  
**License:** MIT  
**Tags:** openaip, aviation, api, integration, tutorial
