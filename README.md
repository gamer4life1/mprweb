# mprweb
mprweb is the platform that powers the makedeb User Repository (MPR).

mprweb is based on the [aurweb](https://gitlab.archlinux.org/archlinux/aurweb) platform, from which almost all of its features are taken.

In addition to the base that aurweb lays, mprweb makes some patches and changes to work better with makedeb and within the Debian/Ubuntu ecosystem.

## Directory layout
Folders are created with the name of the upstream tag (i.e. `v5.0.0` points to the `v5.0.0` tag for aurweb).

Each folder contains all patches already applied, and can simply be downloaded and set up.

## Releases
Releases are periodically made, under the name `[upstream_tag]-patch[number]`, where `[upstream_tag]` is the upstream version, and `[number]` is the revision of said release.

`[number]` will be bumped whenever changes (such as bug fixes) are made, and `[upstreag_tag]` will be changed whenever upstream bumps their version.
