interface fileIconType {
    extension: string;
    srcUrl: string;
    attribute?: string;
}

/**
 * Collection of icon files
 * TODO:  Add More
 */
const fileIconList: fileIconType[] = [
    {
        extension: "pdf",
        srcUrl: "/images/fileIcons/pdf.png",
        attribute:
            '<a href="https://www.flaticon.com/free-icons/png" title="txt file icons">Icon provided by Flaticon</a>',
    },
    {
        extension: "txt",
        srcUrl: "/images/fileIcons/txt-file.png",
        attribute:
            '<a href="https://www.flaticon.com/free-icons/txt-file" title="txt file icons">Txt file icons created by FauzIDEA - Flaticon</a>',
    },
    {
        extension: "csv",
        srcUrl: "/images/fileIcons/csv.png",
        attribute:
            '<a href="https://www.flaticon.com/free-icons/csv" title="csv icons">Csv icons created by mpanicon - Flaticon</a>',
    },
    // {
    //     extension: 'docx',
    //     srcUrl: '/images/fileIcons/docx.png',
    //     attribute: '<a href="https://www.flaticon.com/free-icons/docx" title="docx icons">Docx icons created by kliwir art - Flaticon</a>',
    // }
];

export const getFileIcon = (extension: string): fileIconType | undefined => {
    return fileIconList.find((icon) => icon.extension === extension);
};
