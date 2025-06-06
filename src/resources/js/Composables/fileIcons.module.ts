import type { DropzoneFile } from "dropzone";

type iconPrefix = "viv" | "cla" | "sqo";
type iconSize = "md" | "lg" | "xl";

/*
|-------------------------------------------------------------------------------
| For Dropzone File Input.  Get the icon type belonging to the non image file.
|-------------------------------------------------------------------------------
*/
export const processFileIcon = (file: DropzoneFile): void => {
    // If this is an image file, we do nothing
    if (file.type.split("/")[0] === "image") {
        return;
    }

    // Get the icon for the file type
    let extension: string | undefined = file.name.split(".").pop();
    let icon: string = getFileIcon(extension, "viv", "xl");

    const wrapper: Element =
        file.previewElement.getElementsByClassName("dz-image")[0];

    wrapper.innerHTML = `<div class="dz-icon-wrapper flex flex-col">${icon}<span>{${extension}}</span></div>`;
};

/*
|-------------------------------------------------------------------------------
| Functions to get the icon
|-------------------------------------------------------------------------------
*/
export const getFileIcon = (
    fileExt: string | undefined,
    iconPrefix: iconPrefix = "viv",
    iconSize: iconSize | null = null
): string => {
    if (fileExt !== undefined) {
        if (extendedCatalog.includes(fileExt)) {
            return getExtendedIcon(fileExt, iconSize);
        }

        if (baseCatalog.includes(fileExt)) {
            return getBaseIcon(fileExt, iconPrefix, iconSize);
        }
    }

    let sizeText = getIconSizeText(iconSize);

    return `<span class="fiv-${iconPrefix} fiv-icon-blank ${sizeText}"></span>`;
};

/**
 * Get an icon from the extended library
 */
const getExtendedIcon = (
    fileExt: string | undefined,
    iconSize: iconSize | null = null
): string => {
    let sizeText = getIconSizeText(iconSize);

    return `<span class="fiv-cla fiv-extended-${fileExt} ${sizeText}"></span>`;
};

/**
 * Get an icon from the base library
 */
const getBaseIcon = (
    fileExt: string | undefined,
    iconPrefix: iconPrefix = "viv",
    iconSize: iconSize | null = null
): string => {
    let sizeText = getIconSizeText(iconSize);

    return `<span class="fiv-${iconPrefix} fiv-icon-${fileExt} ${sizeText}"></span>`;
};

/**
 * Get the Icon Size
 */
const getIconSizeText = (iconSize: iconSize | null): string => {
    if (iconSize) {
        return `fiv-size-${iconSize}`;
    }

    return "";
};

/*
|-------------------------------------------------------------------------------
| Icon Library
|-------------------------------------------------------------------------------
*/

/**
 * Additional icons added outside of the file-icon-vectors package
 */
const extendedCatalog = ["pcpx2", "pcpx", "pcp"];

/**
 * All Icons that are part of the file-icon-vectors package
 */
const baseCatalog = [
    "3g2",
    "3ga",
    "3gp",
    "7z",
    "aa",
    "aac",
    "ac",
    "accdb",
    "accdt",
    "adn",
    "ai",
    "aif",
    "aifc",
    "aiff",
    "ait",
    "amr",
    "ani",
    "apk",
    "app",
    "applescript",
    "asax",
    "asc",
    "ascx",
    "asf",
    "ash",
    "ashx",
    "asmx",
    "asp",
    "aspx",
    "asx",
    "au",
    "aup",
    "avi",
    "axd",
    "aze",
    "bak",
    "bash",
    "bat",
    "bin",
    "blank",
    "bmp",
    "bowerrc",
    "bpg",
    "browser",
    "bz2",
    "c",
    "cab",
    "cad",
    "caf",
    "cal",
    "cd",
    "cer",
    "cfg",
    "cfm",
    "cfml",
    "cgi",
    "class",
    "cmd",
    "codekit",
    "coffee",
    "coffeelintignore",
    "com",
    "compile",
    "conf",
    "config",
    "cpp",
    "cptx",
    "cr2",
    "crdownload",
    "crt",
    "crypt",
    "cs",
    "csh",
    "cson",
    "csproj",
    "css",
    "csv",
    "cue",
    "dat",
    "db",
    "dbf",
    "deb",
    "dgn",
    "dist",
    "diz",
    "dll",
    "dmg",
    "dng",
    "doc",
    "docb",
    "docm",
    "docx",
    "dot",
    "dotm",
    "dotx",
    "download",
    "dpj",
    "ds_store",
    "dtd",
    "dwg",
    "dxf",
    "editorconfig",
    "el",
    "enc",
    "eot",
    "eps",
    "epub",
    "eslintignore",
    "exe",
    "f4v",
    "fax",
    "fb2",
    "fla",
    "flac",
    "flv",
    "folder",
    "gadget",
    "gdp",
    "gem",
    "gif",
    "gitattributes",
    "gitignore",
    "go",
    "gpg",
    "gz",
    "h",
    "handlebars",
    "hbs",
    "heic",
    "hs",
    "hsl",
    "htm",
    "html",
    "ibooks",
    "icns",
    "ico",
    "ics",
    "idx",
    "iff",
    "ifo",
    "image",
    "img",
    "in",
    "indd",
    "inf",
    "ini",
    "iso",
    "j2",
    "jar",
    "java",
    "jpe",
    "jpeg",
    "jpg",
    "js",
    "json",
    "jsp",
    "jsx",
    "key",
    "kf8",
    "kmk",
    "ksh",
    "kup",
    "less",
    "lex",
    "licx",
    "lisp",
    "lit",
    "lnk",
    "lock",
    "log",
    "lua",
    "m",
    "m2v",
    "m3u",
    "m3u8",
    "m4",
    "m4a",
    "m4r",
    "m4v",
    "map",
    "master",
    "mc",
    "md",
    "mdb",
    "mdf",
    "me",
    "mi",
    "mid",
    "midi",
    "mk",
    "mkv",
    "mm",
    "mo",
    "mobi",
    "mod",
    "mov",
    "mp2",
    "mp3",
    "mp4",
    "mpa",
    "mpd",
    "mpe",
    "mpeg",
    "mpg",
    "mpga",
    "mpp",
    "mpt",
    "msi",
    "msu",
    "nef",
    "nes",
    "nfo",
    "nix",
    "npmignore",
    "odb",
    "ods",
    "odt",
    "ogg",
    "ogv",
    "ost",
    "otf",
    "ott",
    "ova",
    "ovf",
    "p12",
    "p7b",
    "pages",
    "part",
    "pcd",
    "pdb",
    "pdf",
    "pem",
    "pfx",
    "pgp",
    "ph",
    "phar",
    "php",
    "pkg",
    "pl",
    "plist",
    "pm",
    "png",
    "po",
    "pom",
    "pot",
    "potx",
    "pps",
    "ppsx",
    "ppt",
    "pptm",
    "pptx",
    "prop",
    "ps",
    "ps1",
    "psd",
    "psp",
    "pst",
    "pub",
    "py",
    "pyc",
    "qt",
    "ra",
    "ram",
    "rar",
    "raw",
    "rb",
    "rdf",
    "resx",
    "retry",
    "rm",
    "rom",
    "rpm",
    "rsa",
    "rss",
    "rtf",
    "ru",
    "rub",
    "sass",
    "scss",
    "sdf",
    "sed",
    "sh",
    "sitemap",
    "skin",
    "sldm",
    "sldx",
    "sln",
    "sol",
    "sql",
    "sqlite",
    "step",
    "stl",
    "svg",
    "swd",
    "swf",
    "swift",
    "sys",
    "tar",
    "tcsh",
    "tex",
    "tfignore",
    "tga",
    "tgz",
    "tif",
    "tiff",
    "tmp",
    "torrent",
    "ts",
    "tsv",
    "ttf",
    "twig",
    "txt",
    "udf",
    "vb",
    "vbproj",
    "vbs",
    "vcd",
    "vcs",
    "vdi",
    "vdx",
    "vmdk",
    "vob",
    "vscodeignore",
    "vsd",
    "vss",
    "vst",
    "vsx",
    "vtx",
    "war",
    "wav",
    "wbk",
    "webinfo",
    "webm",
    "webp",
    "wma",
    "wmf",
    "wmv",
    "woff",
    "woff2",
    "wps",
    "wsf",
    "xaml",
    "xcf",
    "xlm",
    "xls",
    "xlsm",
    "xlsx",
    "xlt",
    "xltm",
    "xltx",
    "xml",
    "xpi",
    "xps",
    "xrb",
    "xsd",
    "xsl",
    "xspf",
    "xz",
    "yaml",
    "yml",
    "z",
    "zip",
    "zsh",
];
