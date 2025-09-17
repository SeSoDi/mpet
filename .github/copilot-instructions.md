# mpet - Mario's Pet Project

**Always reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.**

## Project Overview
mpet is currently a minimal starter repository in early development. The project contains only basic files and is set up with a Laravel/PHP-oriented .gitignore, suggesting it may evolve into a PHP/Laravel application.

## Current State
This repository is in its initial stages and contains:
- Basic README.md with project title
- Laravel/PHP .gitignore configuration
- No build system, dependencies, or executable code yet

## Working Effectively

### Repository Structure
```
mpet/
├── .git/
├── .github/
│   └── copilot-instructions.md
├── .gitignore         # Laravel/PHP oriented
└── README.md          # Basic project title
```

### Current Limitations
- **No Build System**: There are currently no build scripts, package managers, or compilation steps.
- **No Dependencies**: No package.json, composer.json, requirements.txt, or other dependency files exist.
- **No Tests**: No test framework or test files are present.
- **No Application Code**: Repository contains only configuration and documentation files.

### Essential Commands
Since this is an early-stage project with no code to build or run:

- `git status` - Check repository status
- `git log --oneline` - View commit history
- `ls -la` - List all files in repository root
- `find . -type f -not -path "./.git/*"` - List all non-git files

### When Code is Added
As the project evolves, update these instructions to include:
- Dependency installation commands (e.g., `composer install` if Laravel, `npm install` if Node.js)
- Build commands with appropriate timeouts (NEVER CANCEL builds - wait for completion)
- Test execution commands with timing expectations
- Application startup procedures
- Validation scenarios for manual testing

### Validation
Currently no validation steps are possible due to lack of executable code. When functionality is added:
- Always test that dependency installation works
- Always verify that build commands complete successfully
- Always run the full test suite before finalizing changes
- Always manually test core application functionality

### Future Development Considerations
Based on the .gitignore file, this project may become a Laravel/PHP application. When that happens:
- Install PHP and Composer dependencies
- Set up database connections if needed
- Configure environment variables (.env files)
- Run Laravel-specific commands (artisan, migrations, etc.)

## Common Patterns
Since this is a starter project, common patterns will emerge as code is added. Update this section with:
- File organization patterns
- Naming conventions used
- Code architecture decisions
- Development workflow preferences

## Important Notes
- **NEVER CANCEL** long-running builds or tests when they are eventually added
- Always set appropriate timeouts (60+ minutes for builds, 30+ minutes for tests)
- This project is in early development - these instructions should be updated as the codebase grows
- The Laravel-oriented .gitignore suggests future PHP/Laravel development direction