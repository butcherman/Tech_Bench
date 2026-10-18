const appName: string = appData.name;
const companyName: string = appData.company_name;
const logo: string = appData.logo;
const version: string = appData.version;
const copyright: string = appData.copyright;

export const useAppData = () => {
    return {
        appName,
        companyName,
        logo,
        version,
        copyright,
    };
};
