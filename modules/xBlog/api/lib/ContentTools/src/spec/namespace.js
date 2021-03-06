// Generated by CoffeeScript 1.10.0
describe('ContentTools.getEmbedVideoURL()', function() {
  it('should return a valid video embbed URL from a youtube URL', function() {
    var embedURL, insecureURL, pageURL, shareURL;
    embedURL = 'https://www.youtube.com/embed/t4gjl-uwUHc';
    expect(ContentTools.getEmbedVideoURL(embedURL)).toBe(embedURL);
    shareURL = 'https://youtu.be/t4gjl-uwUHc';
    expect(ContentTools.getEmbedVideoURL(shareURL)).toBe(embedURL);
    pageURL = 'https://www.youtube.com/watch?v=t4gjl-uwUHc';
    expect(ContentTools.getEmbedVideoURL(pageURL)).toBe(embedURL);
    insecureURL = 'http://www.youtube.com/watch?v=t4gjl-uwUHc';
    return expect(ContentTools.getEmbedVideoURL(insecureURL)).toBe(embedURL);
  });
  return it('should return a valid video embbed URL from a vimeo URL', function() {
    var embedURL, insecureURL, pageURL;
    embedURL = 'https://player.vimeo.com/video/1084537';
    expect(ContentTools.getEmbedVideoURL(embedURL)).toBe(embedURL);
    pageURL = 'https://vimeo.com/1084537';
    expect(ContentTools.getEmbedVideoURL(pageURL)).toBe(embedURL);
    insecureURL = 'http://vimeo.com/1084537';
    return expect(ContentTools.getEmbedVideoURL(insecureURL)).toBe(embedURL);
  });
});
