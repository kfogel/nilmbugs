# Default rule will probably change to 'build' once the PeoplePods
# provisioning is sorted out.  For now, the 'build' rule does nothing.

default: dist

build:
	@./build.sh

dist:
	@./dist.sh
