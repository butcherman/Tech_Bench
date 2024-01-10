const Ziggy = {"url":"https:\/\/192.168.1.250","port":null,"defaults":{},"routes":{"logout":{"uri":"logout","methods":["POST"]},"password.email":{"uri":"forgot-password","methods":["POST"]},"password.update":{"uri":"reset-password","methods":["POST"]},"user-password.update":{"uri":"user\/password","methods":["PUT"]},"password.confirmation":{"uri":"user\/confirmed-password-status","methods":["GET","HEAD"]},"password.confirm":{"uri":"user\/confirm-password","methods":["POST"]},"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"telescope":{"uri":"administration\/telescope\/{view?}","methods":["GET","HEAD"],"wheres":{"view":"(.*)"},"parameters":["view"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };
