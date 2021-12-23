# Wikispecies parser

Parse references in Wikispecies.

## Releases

[![DOI](https://zenodo.org/badge/440792353.svg)](https://zenodo.org/badge/latestdoi/440792353)

## Wikispecies dumps

Dumps are available from https://dumps.wikimedia.org/backup-index.html

## Parsing Wikispecies references

Use local version of (acoustic-bandana)[https://acoustic-bandana.glitch.me]. Download source from Glitch, then:
- cd to app directory
- `npm install`
- `npm start server.js`

Service will then be available on http://localhost:3000 The service takes a Wikispecies reference string and returns [CSL-JSON](https://citation.js.org).

Then run

```
php extract-from-dump.php
```

Which will parse the hard-coded link to the XML dump, extract references, call the `acoustic-bandana` parser, and output references in CSL-JSON.

To get the number of references in the file type `wc -l all.jsonl`.

First attempt found 195598 references.
Second attempt with template parsing found 260152 references.


