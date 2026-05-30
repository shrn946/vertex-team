# Changelog

All notable changes to this plugin should be documented in this file.

The format is based on Keep a Changelog and this project follows Semantic Versioning.

## [2.1.0] - 2026-05-30

### Added

- Dynamic social icons repeater for each team member:
  - Icon selector (Elementor Icon Library)
  - URL field
  - Open in new tab option
  - Nofollow option
- Optional View Info button toggle per member.
- Button text and URL controls per member.
- Social icon style controls:
  - Size
  - Color
  - Hover color
  - Spacing
- Button style controls:
  - Typography
  - Normal/hover text and background colors
  - Border radius
  - Padding
  - Alignment
- Card background style control with classic and gradient support.

### Changed

- Plugin renamed to **Vertex Team Carousel for Elementor**.
- Author changed to **Apex Themes Studio**.
- Text domain updated to `apex-team-carousel-elementor`.
- Default social icon color set to white (`#FFFFFF`).
- Default card gradient colors set to:
  - `#752E2E`
  - `#F2295B`
- Default team title font size set to `26px`.
- Default View Info button state set to hidden.
- Updated dummy team member names and titles.
- Restored demo member images (Pexels URLs) for default content.

### Fixed

- Enforced `text-decoration: none;` on View Info button links.
- Output rendering now avoids empty social icon blocks when no icons are defined.
