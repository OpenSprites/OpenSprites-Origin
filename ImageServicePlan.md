#Plan:
##OpenSprites Image Hosting Service

###Introduction
At present, a Scratch user who wishes to embed an image into a forum post is required to upload the image to a third-party hosting service that has been white-listed by the Scratch Team. The majority of the white-listed hosting services are not intended for use by children, and therefore display advertising banners that may not be appropriate for young ages. There is also the risk that unsuitable images uploaded by other web users held on a white-listed host can be posted onto the Scratch forums.

###Previous Plan
It has been a long proposed idea to integrate image hosting directly into the OpenSprites website, which does not have the aforementioned issues; this would provide a safe and monitored facility to host images intended for the forums. However, due to the complexity of modifying the behaviour between resources and images intended for hosting (these would need to be hidden from the rest of the website, yet still be visible to staff and the owner), it has become a less attractive option. This is also not considering recent issues regarding the rejection of certain image formats by the website.

###Proposed Plan
It has been decided that to avoid the issue of compatibility with main OpenSprites website, a separate hosting facility will be built from the ground up; providing a clean code-base and efficient structuring. 
	After a (suspicious) poll, it was decided that the service will be built in Node.js, rather than PHP; Node is not only a more recent web-technology, but also offers higher performance in handling requests than PHP which is crucial for responding quickly with images.
	While more developers on the OpenSprites team are familiar with PHP, I have been reliably informed that Node.js is sufficiently easy to pick up if one has knowledge of JavaScript. It should also be noted that the use of dated technologies, such as PHP, is frowned upon within the advanced community.
	
###Plan Details
#####Minimum Requirements
The image hosting service has a few minimum requirements which are necessary to insure full integration with the main OpenSprites website.
* The requirement to log-in with a existing OpenSprites account.
* High level of accepted image formats.
* Fast response times.
* A moderator control panel where recently uploaded images can be screened for inappropriate content.
* Images 30 days old which have never been embedded are erased, along with images which have not been accessed for 250 days.
* Linking to images on Scratch only.
* A name for the service.

#####Preferred Extras
* The ability for a user to delete and view their images.
* Low/no compression.
* SSL - some browsers suppress content from third-parties if the current site uses SSL and the third-party does not.

#####Pros
* As outlined above, OpenSprites is committed to not displaying adverts, which keeps content appropriate for young users.
* Images uploaded by other users are not visible.
* Recent images are screened by moderators for inappropriate content.
* OpenSprites had a duty to Scratch, and images can be removed immediately at the request of the Scratch Team.
* Starting afresh with Node.js gives the opportunity to begin with a clean slate, which means the service can be written efficiently and have less dependency on the main website.

#####Cons/Issues
* OpenSprites currently has limited disk space and bandwidth, which means that old images (see above) will need to be overwritten and images can only be shared on Scratch.
* OpenSprites would need to be added to the Scratch images white-list. It be granted this, the service would need to be proved reliable, stable and secure.
* Moderation would require the recruiting of responsible volunteers.
* Development of OpenSprites is notoriously easygoing.

###Development Structure
The development 'check-list' is as follows:
1. Design a graphical mock-up of the user interface and decide on what to use. While the design will take on the styling of the main website, each screen and menu will need to be considered.

2. Using the output from point one, decide on how the back-end will be structured and what libraries or frameworks will be needed.

3. Develop the back-end.

4. Develop the frond end user interface.

5. A testing environment will be set-to allow testing and bug-fixing.

6. Invite users to trial a public beta with Scratch restricted embedding disabled.

7. Final release.

###Feedback
Constructive criticism is crucial, so if you think something needs to be modified, removed or added, please say.
